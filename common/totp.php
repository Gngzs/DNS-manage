<?php
require_once '../vendor/autoload.php';
require_once "../common/clock.php";

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use OTPHP\TOTP;
use Clock\SystemClock;

class ymtotp
{
    private static function GetTotpUrl($name, $secret, $title = null)
    {
        $url = "otpauth://totp/$name?secret=$secret";
        if (isset($title)) {
            $url .= "&issuer=$title";
        }
        return $url;
    }
    public static function CreatQR($username, $secret)
    {
        $qrCodeUrl = ymtotp::GetTotpUrl($username, $secret, "YM域名解析管理系统");

        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($qrCodeUrl)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            //->logoPath(__DIR__ . '/assets/symfony.png')
            //->logoResizeToWidth(50)
            //->logoPunchoutBackground(true)
            //->labelText('This is the label')
            //->labelFont(new NotoSans(20))
            //->labelAlignment(LabelAlignment::Center)
            ->validateResult(false)
            ->build();

        // 输出二维码图像
        header('Content-Type: ' . $result->getMimeType());
        echo $result->getString();
    }

    //验证
    public static function VerifyCode($secret, $code)
    {
        $digits = 6;
        $digest = 'sha1';
        $period = 30;
        $clock = new SystemClock();
        $totp = TOTP::createFromSecret($secret, $clock);
        $totp->setPeriod($period);
        $totp->setDigest($digest);
        $totp->setDigits($digits);
        return $totp->verify($code); //bool
    }
    //生成密钥
    public static function CreatSecret()
    {
        $clock = new SystemClock();
        $otp = TOTP::generate($clock);
        return $otp->getSecret();
    }
}
