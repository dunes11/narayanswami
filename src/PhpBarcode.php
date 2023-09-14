<?php

namespace PHPMaker2023\demo2023;

use \Intervention\Image\ImageManagerStatic;

/**
 * PHP Barcode class
 */
class PhpBarcode
{
    /**
     * Path of barcode.php
     */
    public $Path = "";

    /**
     * Use PhpSpreadsheet
     */
    public $UsePhpExcel = false;

    /**
     * Use PhpWord
     */
    public $UsePhpWord = false;

    /**
     * Show text under barcode
     */
    public $ShowText = false;

    /**
     * Text font properties (size, color, angle, align, valign, kerning, file)
     * See https://github.com/Intervention/image/blob/master/src/Intervention/Image/AbstractFont.php
     */
    public static $TextFont = [
        "file" => "font/DejaVuSans.ttf", // 1-5 (gd internal font) or .ttf file path
        "size" => 12,
        "align" => "center",
        "valign" => "bottom"
    ];

    /**
     * Text y-padding
     */
    public static $TextPadding = 6;

    /**
     * Barcode padding
     * Additional padding to add around the barcode (top, right, bottom, left) in user units.
     * A negative value indicates the multiplication factor for each row or column.
     */
    public static $Padding = [0, 0, 0, 0];

    /**
     * Background color
     */
    public static $BackgroundColor = "white";

    /**
     * Width (use absolute or negative value as multiplication factor)
     */
    public static $Width = -2;

    /**
     * Get PhpBarcode instance
     *
     * @return PhpBarcode
     */
    public static function barcode(bool $showText = false)
    {
        $barcode = new static();
        $barcode->ShowText = $showText;
        return $barcode;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->UsePhpExcel = Config("USE_PHPEXCEL");
        $this->UsePhpWord = Config("USE_PHPWORD");
        $this->Path = GetUrl("barcode");
        // Always use full path for font file
        $fontFile = self::$TextFont["file"];
        if (!is_numeric($fontFile)) {
            if (!ContainsString($fontFile, ".")) {
                $fontFile .= ".ttf";
            }
            if (file_exists($fontFile)) { // Get absolute path
                self::$TextFont["file"] = realpath($fontFile);
            }
        }
    }

    /**
     * Get barcode PNG data
     *
     * @param string $code Barcode content
     * @param string $type Barcode type
     * @param int $height Barcode height excluding padding. A negative value indicates the multiplication factor for each row.
     * @param string $color Foreground color in web notation (color name, or hexadecimal code, or CSS syntax)
     * @return string Barcode as PNG data
     */
    public function getData($code, $type, $height = 0, $color = "#000000")
    {
        if ($code == "") {
            throw new \Exception("Missing barcode content");
        }
        if (!$type) {
            throw new \Exception("Missing barcode type");
        }
        $type = strtoupper(str_replace("-", "", $type));
        $btype = $type;
        $width = self::$Width;
        if ($type == "ISBN") {
            $btype = "EAN13";
        } elseif ($type == "CODE128" || $type == "CODE39" || $type == "CODE93") {
            $btype = str_replace("ODE", "", $type);
        }
        $barcode = new \Com\Tecnick\Barcode\Barcode(); // \Com\Tecnick\Barcode\Barcode
        if (!in_array($btype, $barcode->getTypes())) {
            throw new \Exception("Barcode type '" . $type . "' not supported");
        }
        if ($type == "QRCODE") {
            $btype = "QRCODE,H";
            $width = $height;
        }
        $code = trim(preg_replace('/[\r\n ]{2,}/', " ", $code)); // Replace line breaks
        $img = $barcode->getBarcodeObj(
            $btype, // Barcode type and additional comma-separated parameters
            $code, // Data string to encode
            $width, // Bar width (use absolute or negative value as multiplication factor)
            $height, // Bar height (use absolute or negative value as multiplication factor)
            $color, // Foreground color
            self::$Padding // Padding (use absolute or negative values as multiplication factors)
        )->setBackgroundColor(self::$BackgroundColor) // Background color
        ->getPngData();
        if ($this->ShowText) {
            $img = ImageManagerStatic::make($img);
            $width = $img->width();
            $height = $img->height();
            $newHeight = $height + self::$TextFont["size"] + self::$TextPadding; // Increase height for text
            $img->resizeCanvas($width, $newHeight, "top");
            $img->text($code, intdiv($width, 2), $newHeight, function ($font) use ($color) {
                $font->color($color);
                foreach (self::$TextFont as $prop => $value) {
                    if (method_exists($font, $prop)) {
                        $font->$prop($value);
                    }
                }
            });
            $img = (string)$img->encode("png", 100);
        }
        return $img;
    }

    /**
     * Write barcode
     *
     * @param string $code Barcode data
     * @param string $type Barcode type
     * @param int $height Barcode height
     * @param string $color Barcode color
     * @return void
     */
    public function write($code, $type, $height = 0, $color = "#000000")
    {
        try {
            $data = $this->getData($code, $type, $height, $color);
            WriteHeader(false);
            AddHeader("Content-Type", "image/png");
            Write($data);
        } catch (\Throwable $e) {
            Log("Failed to generate '" . $type . "' barcode for '" . $code . "'");
            if (Config("DEBUG")) {
                throw $e;
            }
        }
    }

    /**
     * Show barcode
     *
     * @param string $code Barcode data
     * @param string $type Barcode type
     * @param int $height Barcode height
     * @param string $color Barcode color
     * @return string HTML tag or href value
     */
    public function show($code, $type, $height = 0, $color = "#000000")
    {
        global $ExportType;
        if (EmptyString($code)) {
            return "";
        }
        if (!$ExportType || $ExportType == "print") {
            $url = "data=" . urlencode($code) . "&amp;encode=" . urlencode($type);
            if ($height > 0) {
                $url .= "&amp;height=" . urlencode($height);
            }
            if ($color != "") {
                $url .= "&amp;color=" . urlencode($color);
            }
            if ($this->ShowText) {
                $url .= "&amp;text=1";
            }
            if (IsLazy()) {
                return "<img class=\"ew-lazy ew-barcode\" loading=\"lazy\" src=\"data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=\" data-src=\"" . $this->Path . "?" . $url . "\" alt=\"\">";
            } else {
                return "<img class=\"ew-barcode\" src=\"" . $this->Path . "?" . $url . "\" alt=\"\">";
            }
        } elseif ($ExportType == "pdf" || $ExportType == "email") {
            return "<img class=\"ew-barcode\" src=\"" . $this->getHrefValue($code, $type, $height, $color, $ExportType) . "\">";
        } elseif ($ExportType == "excel" && $this->UsePhpExcel || $ExportType == "word" && $this->UsePhpWord) {
            return $this->getHrefValue($code, $type, $height, $color, $ExportType);
        }
        return $code;
    }

    /**
     * Get barcode as href value
     *
     * @param string $code Barcode data
     * @param string $type Barcode type
     * @param int $height Barcode height
     * @param string $color Barcode color
     * @param string $export Export format
     * @return string Href value
     */
    public function getHrefValue($code, $type, $height = 0, $color = "#000000", $export = "")
    {
        if (EmptyString($code)) {
            return "";
        }
        try {
            $data = $this->getData($code, $type, $height, $color);
            return TempImage($data);
        } catch (\Throwable $e) {
            Log("Failed to generate '" . $type . "' barcode for '" . $code . "'");
            if (Config("DEBUG")) {
                throw $e;
            }
        }
    }
}
