<?php

if (! function_exists('fmt_qty')) {
    /**
     * Format a quantity with smart unit display.
     * - Count units (Pcs, Box, Pair, etc.) → integer: "100"
     * - Measurement units (Meter, CM, Ft, Kg, etc.) → with label: "100m", "50kg"
     */
    function fmt_qty(float|int|string $quantity, string $unit): string
    {
        $qty = (float) $quantity;

        $countUnits = ['pcs', 'box', 'pair', 'set', 'roll', 'each', 'unit', 'nos', 'piece', 'pieces'];

        if (in_array(strtolower($unit), $countUnits)) {
            return number_format((int) $qty);
        }

        // For measurement units — strip trailing zeros and append unit label
        $formatted = rtrim(number_format($qty, 2, '.', ''), '0');
        $formatted = rtrim($formatted, '.');

        return $formatted . strtolower($unit);
    }
}
