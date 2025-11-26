<?php
// app/Helpers/ChartDataHelper.php

class ChartDataHelper {
    
    /**
     * Format data for line chart (Chart.js)
     */
    public static function formatLineChart($data, $labelKey, $valueKey, $label = 'Data') {
        $labels = [];
        $values = [];
        
        foreach ($data as $item) {
            $labels[] = $item[$labelKey];
            $values[] = (float)$item[$valueKey];
        }
        
        return [
            'labels' => $labels,
            'datasets' => [[
                'label' => $label,
                'data' => $values,
                'borderColor' => 'rgb(75, 192, 192)',
                'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                'tension' => 0.4
            ]]
        ];
    }

    /**
     * Format data for pie/doughnut chart
     */
    public static function formatPieChart($data, $labelKey, $valueKey) {
        $labels = [];
        $values = [];
        
        foreach ($data as $item) {
            $labels[] = $item[$labelKey];
            $values[] = (float)$item[$valueKey];
        }
        
        return [
            'labels' => $labels,
            'datasets' => [[
                'data' => $values,
                'backgroundColor' => self::generateColors(count($data)),
                'borderWidth' => 1
            ]]
        ];
    }

    /**
     * Format data for bar chart
     */
    public static function formatBarChart($data, $labelKey, $valueKey, $label = 'Data') {
        $labels = [];
        $values = [];
        
        foreach ($data as $item) {
            $labels[] = $item[$labelKey];
            $values[] = (float)$item[$valueKey];
        }
        
        return [
            'labels' => $labels,
            'datasets' => [[
                'label' => $label,
                'data' => $values,
                'backgroundColor' => 'rgba(54, 162, 235, 0.7)',
                'borderColor' => 'rgb(54, 162, 235)',
                'borderWidth' => 1
            ]]
        ];
    }

    /**
     * Generate color palette
     */
    public static function generateColors($count) {
        $colors = [
            'rgba(255, 99, 132, 0.7)',   // Red
            'rgba(54, 162, 235, 0.7)',   // Blue
            'rgba(255, 206, 86, 0.7)',   // Yellow
            'rgba(75, 192, 192, 0.7)',   // Teal
            'rgba(153, 102, 255, 0.7)',  // Purple
            'rgba(255, 159, 64, 0.7)',   // Orange
            'rgba(99, 255, 132, 0.7)',   // Green
            'rgba(235, 54, 162, 0.7)',   // Pink
            'rgba(86, 255, 206, 0.7)',   // Cyan
            'rgba(192, 75, 192, 0.7)',   // Magenta
        ];
        
        // If need more colors, repeat the palette
        if ($count > count($colors)) {
            $colors = array_merge($colors, $colors, $colors);
        }
        
        return array_slice($colors, 0, $count);
    }

    /**
     * Format multi-dataset line chart
     */
    public static function formatMultiLineChart($datasets, $labelKey, $valueKey) {
        $allLabels = [];
        $chartDatasets = [];
        
        $colors = [
            ['border' => 'rgb(75, 192, 192)', 'bg' => 'rgba(75, 192, 192, 0.2)'],
            ['border' => 'rgb(255, 99, 132)', 'bg' => 'rgba(255, 99, 132, 0.2)'],
            ['border' => 'rgb(54, 162, 235)', 'bg' => 'rgba(54, 162, 235, 0.2)'],
            ['border' => 'rgb(255, 206, 86)', 'bg' => 'rgba(255, 206, 86, 0.2)'],
        ];
        
        $colorIndex = 0;
        foreach ($datasets as $name => $data) {
            $values = [];
            foreach ($data as $item) {
                if (!in_array($item[$labelKey], $allLabels)) {
                    $allLabels[] = $item[$labelKey];
                }
                $values[] = (float)$item[$valueKey];
            }
            
            $color = $colors[$colorIndex % count($colors)];
            $chartDatasets[] = [
                'label' => $name,
                'data' => $values,
                'borderColor' => $color['border'],
                'backgroundColor' => $color['bg'],
                'tension' => 0.4
            ];
            $colorIndex++;
        }
        
        return [
            'labels' => $allLabels,
            'datasets' => $chartDatasets
        ];
    }
    
    /**
     * Convert month number to name
     */
    public static function monthName($monthStr) {
        $months = [
            '01' => 'Ene', '02' => 'Feb', '03' => 'Mar',
            '04' => 'Abr', '05' => 'May', '06' => 'Jun',
            '07' => 'Jul', '08' => 'Ago', '09' => 'Sep',
            '10' => 'Oct', '11' => 'Nov', '12' => 'Dic'
        ];
        
        // Extract month from YYYY-MM format
        $parts = explode('-', $monthStr);
        if (count($parts) == 2) {
            return $months[$parts[1]] . ' ' . $parts[0];
        }
        
        return $monthStr;
    }

    /**
     * Format month labels for charts
     */
    public static function formatMonthLabels($data, $monthKey) {
        foreach ($data as &$item) {
            $item[$monthKey] = self::monthName($item[$monthKey]);
        }
        return $data;
    }
}
