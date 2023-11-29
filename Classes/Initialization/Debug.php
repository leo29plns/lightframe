<?php

namespace lightframe\Initialization;

class Debug
{
    private const LOG_FILE = 'lightframe.log';

    public static function dump(...$values) : void 
    {
        $die = false;

        if (!empty($values) && is_bool($values[count($values) - 1])) {
            $die = array_pop($values);
        }

        echo '<pre style="background-color: #f5f5f5; padding: 10px; border: 1px solid #ccc; color: #333;">';

        foreach ($values as $name => $value) {
            echo '<strong>' . $name . ':</strong><br>';
            $highlightedValue = highlight_string("<?php\n" . var_export($value, true) . ";\n", true);
            $highlightedValue = str_replace(['&lt;?php', '=&nbsp;'], '', $highlightedValue);
            echo $highlightedValue;
            echo '<br>';
        }

        echo '</pre>';

        if ($die) {
            echo '<table style="border-collapse: collapse; float: right;">';
            echo '<caption style="font-weight: bold; padding: 5px;">PHP Information</caption>';

            $phpInfo = [
                'PHP Version' => phpversion(),
                'Memory Usage' => self::convertMemoryUsage(memory_get_usage()),
                'Peak Memory Usage' => self::convertMemoryUsage(memory_get_peak_usage()),
                'Execution Time' => (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) . ' seconds',
                'Timestamp' => date('Y-m-d H:i:s')
            ];

            foreach ($phpInfo as $key => $value) {
                echo '<tr>';
                echo '<td style="border: 1px solid #ccc; padding: 5px; font-weight: bold;">' . $key . ':</td>';

                if (is_array($value)) {
                    echo '<td style="border: 1px solid #ccc; padding: 5px; background-color: #f0f0f0;">';
                    $highlightedValue = highlight_string("<?php\n" . var_export($value, true) . ";\n", true);
                    $highlightedValue = str_replace(['&lt;?php', '=&nbsp;'], '', $highlightedValue);
                    echo $highlightedValue;
                    echo '</td>';
                } else {
                    echo '<td style="border: 1px solid #ccc; padding: 5px;">' . $value . '</td>';
                }

                echo '</tr>';
            }
            echo '</table>';

            die();
        }
    }

    private static function convertMemoryUsage(int $size) : string
    {
        $units = ['bytes', 'KB', 'MB', 'GB', 'TB'];
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 2) . ' ' . $units[$unitIndex];
    }

    public static function log(string $message, $level = 1) : void
    {
        if (!file_exists(self::LOG_FILE)) {
            touch(self::LOG_FILE);
        }

        $levels = ['INFO ', 'DEBUG', 'WARN ', 'ERROR', 'FATAL'];
        $timestamp = date('Y-m-d H:i:s');

        if ($level > (count($levels) - 1)) {
            $level = count($levels) - 1;
        }

        $logEntry = "[$timestamp][$levels[$level]] $message\n";

        file_put_contents(self::LOG_FILE, $logEntry, FILE_APPEND);
    }

    public static function deleteLogs() : void
    {
        unlink(self::LOG_FILE);
    }
}