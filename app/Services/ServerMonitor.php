<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ServerMonitor
{
    public function getResources()
    {
        return [
            'cpu_usage' => $this->getCpuUsage(),
            'memory_usage' => $this->getMemoryUsage(),
            'disk_usage' => $this->getDiskUsage(),
            'active_connections' => $this->getActiveConnections(),
            'queue_length' => $this->getQueueLength(),
            'uptime' => $this->getUptime(),
            'memory_used' => $this->getMemoryUsed(),
            'memory_total' => $this->getMemoryTotal(),
            'disk_used' => $this->getDiskUsed(),
            'disk_total' => $this->getDiskTotal(),
            'system_status' => $this->getSystemStatus(),
            'timestamp' => now()->format('H:i:s'),
        ];
    }
    
    private function getCpuUsage()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $output = shell_exec('wmic cpu get loadpercentage');
            preg_match('/\d+/', $output, $matches);
            return $matches[0] ?? 0;
        } else {
            $load = sys_getloadavg();
            return round($load[0] * 100 / 4, 2);
        }
    }
    
    private function getMemoryUsage()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $output = shell_exec('wmic os get TotalVisibleMemorySize,FreePhysicalMemory /value');
            parse_str(str_replace("\r", "", $output), $data);
            
            if (isset($data['TotalVisibleMemorySize']) && isset($data['FreePhysicalMemory'])) {
                $total = $data['TotalVisibleMemorySize'] / 1024 / 1024;
                $free = $data['FreePhysicalMemory'] / 1024 / 1024;
                $used = $total - $free;
                
                if ($total > 0) {
                    return round(($used / $total) * 100, 2);
                }
            }
            return 0;
        } else {
            $data = file_get_contents('/proc/meminfo');
            preg_match('/MemTotal:\s+(\d+)/', $data, $total);
            preg_match('/MemAvailable:\s+(\d+)/', $data, $available);
            if (isset($total[1]) && isset($available[1])) {
                return round(($total[1] - $available[1]) / $total[1] * 100, 2);
            }
            return 0;
        }
    }
    
    private function getDiskUsage()
    {
        $path = '/';
        if (PHP_OS_FAMILY === 'Windows') {
            $path = 'C:';
        }
        $total = disk_total_space($path);
        $free = disk_free_space($path);
        if ($total > 0) {
            return round(($total - $free) / $total * 100, 2);
        }
        return 0;
    }
    
    private function getUptime()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            // Windows uptime in seconds
            $output = shell_exec('wmic os get lastbootuptime');
            if ($output) {
                preg_match('/\d{14}/', $output, $matches);
                if (isset($matches[0])) {
                    $bootTime = \DateTime::createFromFormat('YmdHis', $matches[0]);
                    if ($bootTime) {
                        $uptime = time() - $bootTime->getTimestamp();
                        $days = floor($uptime / 86400);
                        $hours = floor(($uptime % 86400) / 3600);
                        $minutes = floor(($uptime % 3600) / 60);
                        return "{$days}d {$hours}h {$minutes}m";
                    }
                }
            }
            return 'N/A';
        } else {
            $uptime = shell_exec('cat /proc/uptime');
            $uptime = explode(' ', $uptime)[0];
            $days = floor($uptime / 86400);
            $hours = floor(($uptime % 86400) / 3600);
            $minutes = floor(($uptime % 3600) / 60);
            return "{$days}d {$hours}h {$minutes}m";
        }
    }
    
    private function getActiveConnections()
    {
        try {
            $connections = DB::select('SHOW STATUS WHERE `variable_name` = "Threads_connected"');
            return $connections[0]->Value ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    private function getQueueLength()
    {
        return 0;
    }
    
    private function getSystemStatus()
    {
        // Check Python AI
        try {
            $response = Http::get('http://localhost:8001/health');
            $pythonStatus = $response->successful() ? 'Operational' : 'Down';
        } catch (\Exception $e) {
            $pythonStatus = 'Down';
        }
        
        // Check Database
        try {
            DB::connection()->getPdo();
            $dbStatus = 'Operational';
        } catch (\Exception $e) {
            $dbStatus = 'Down';
        }
        
        $services = [
            'Laravel' => 'Operational',
            'Python AI' => $pythonStatus,
            'Database' => $dbStatus,
            'Redis' => 'Not Configured',
        ];
        
        $allOperational = !in_array('Down', array_values($services));
        
        return [
            'status' => $allOperational ? 'All Services Operational ✅' : 'Some Services Down ⚠️',
            'services' => $services,
        ];
    }
    
    private function getMemoryUsed()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $output = shell_exec('wmic os get TotalVisibleMemorySize,FreePhysicalMemory /value');
            parse_str(str_replace("\r", "", $output), $data);
            
            if (isset($data['TotalVisibleMemorySize']) && isset($data['FreePhysicalMemory'])) {
                $total = $data['TotalVisibleMemorySize'] / 1024 / 1024;
                $free = $data['FreePhysicalMemory'] / 1024 / 1024;
                return round($total - $free, 2);
            }
            return 0;
        } else {
            $data = file_get_contents('/proc/meminfo');
            preg_match('/MemTotal:\s+(\d+)/', $data, $total);
            preg_match('/MemAvailable:\s+(\d+)/', $data, $available);
            if (isset($total[1]) && isset($available[1])) {
                return round(($total[1] - $available[1]) / 1024 / 1024, 2);
            }
            return 0;
        }
    }
    
    private function getMemoryTotal()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $output = shell_exec('wmic os get TotalVisibleMemorySize /value');
            preg_match('/TotalVisibleMemorySize=(\d+)/', $output, $total);
            return isset($total[1]) ? round($total[1] / 1024 / 1024, 2) : 0;
        } else {
            $data = file_get_contents('/proc/meminfo');
            preg_match('/MemTotal:\s+(\d+)/', $data, $total);
            return isset($total[1]) ? round($total[1] / 1024 / 1024, 2) : 0;
        }
    }
    
    private function getDiskUsed()
    {
        $path = '/';
        if (PHP_OS_FAMILY === 'Windows') {
            $path = 'C:';
        }
        $total = disk_total_space($path);
        $free = disk_free_space($path);
        return round(($total - $free) / 1024 / 1024 / 1024, 2);
    }
    
    private function getDiskTotal()
    {
        $path = '/';
        if (PHP_OS_FAMILY === 'Windows') {
            $path = 'C:';
        }
        return round(disk_total_space($path) / 1024 / 1024 / 1024, 2);
    }
}