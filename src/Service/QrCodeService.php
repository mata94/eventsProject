<?php

namespace App\Service;


use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\Mime\Part\DataPart;

class QrCodeService
{
    /**
     * @param string $data
     * @param string $filename
     * @return DataPart
     */
    public function generateQrCode(string $data,string $filename = 'qrcode.png'): DataPart
    {
        $link = "https://aluminij.hunterdev.pro/public/slot/".$data."/checked";

        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($link)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(200)
            ->margin(10)
            ->build();

        return new DataPart($result->getString(), $filename, 'image/png');
    }
}