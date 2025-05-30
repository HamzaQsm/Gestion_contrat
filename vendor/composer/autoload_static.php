<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2185d2f99bcd56787481d9357a5972d3
{
    public static $files = array (
        '79f66bc0a1900f77abe4a9a299057a0a' => __DIR__ . '/..' . '/starkbank/ecdsa/src/ellipticcurve.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'SendGrid\\Stats\\' => 15,
            'SendGrid\\Mail\\' => 14,
            'SendGrid\\Helper\\' => 16,
            'SendGrid\\EventWebhook\\' => 22,
            'SendGrid\\Contacts\\' => 18,
            'SendGrid\\' => 9,
        ),
        'P' => 
        array (
            'PhpOffice\\PhpWord\\' => 18,
            'PhpOffice\\Math\\' => 15,
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'E' => 
        array (
            'EllipticCurve\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'SendGrid\\Stats\\' => 
        array (
            0 => __DIR__ . '/..' . '/sendgrid/sendgrid/lib/stats',
        ),
        'SendGrid\\Mail\\' => 
        array (
            0 => __DIR__ . '/..' . '/sendgrid/sendgrid/lib/mail',
        ),
        'SendGrid\\Helper\\' => 
        array (
            0 => __DIR__ . '/..' . '/sendgrid/sendgrid/lib/helper',
        ),
        'SendGrid\\EventWebhook\\' => 
        array (
            0 => __DIR__ . '/..' . '/sendgrid/sendgrid/lib/eventwebhook',
        ),
        'SendGrid\\Contacts\\' => 
        array (
            0 => __DIR__ . '/..' . '/sendgrid/sendgrid/lib/contacts',
        ),
        'SendGrid\\' => 
        array (
            0 => __DIR__ . '/..' . '/sendgrid/php-http-client/lib',
        ),
        'PhpOffice\\PhpWord\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoffice/phpword/src/PhpWord',
        ),
        'PhpOffice\\Math\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoffice/math/src/Math',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'EllipticCurve\\' => 
        array (
            0 => __DIR__ . '/..' . '/starkbank/ecdsa/src',
        ),
    );

    public static $classMap = array (
        'BaseSendGridClientInterface' => __DIR__ . '/..' . '/sendgrid/sendgrid/lib/BaseSendGridClientInterface.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Datamatrix' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/barcodes/datamatrix.php',
        'EllipticCurve\\Curve' => __DIR__ . '/..' . '/starkbank/ecdsa/src/curve.php',
        'EllipticCurve\\Ecdsa' => __DIR__ . '/..' . '/starkbank/ecdsa/src/ecdsa.php',
        'EllipticCurve\\Math' => __DIR__ . '/..' . '/starkbank/ecdsa/src/math.php',
        'EllipticCurve\\Point' => __DIR__ . '/..' . '/starkbank/ecdsa/src/point.php',
        'EllipticCurve\\PrivateKey' => __DIR__ . '/..' . '/starkbank/ecdsa/src/privatekey.php',
        'EllipticCurve\\PublicKey' => __DIR__ . '/..' . '/starkbank/ecdsa/src/publickey.php',
        'EllipticCurve\\Signature' => __DIR__ . '/..' . '/starkbank/ecdsa/src/signature.php',
        'EllipticCurve\\Utils\\Binary' => __DIR__ . '/..' . '/starkbank/ecdsa/src/utils/binary.php',
        'EllipticCurve\\Utils\\Der' => __DIR__ . '/..' . '/starkbank/ecdsa/src/utils/der.php',
        'EllipticCurve\\Utils\\DerFieldType' => __DIR__ . '/..' . '/starkbank/ecdsa/src/utils/der.php',
        'EllipticCurve\\Utils\\File' => __DIR__ . '/..' . '/starkbank/ecdsa/src/utils/file.php',
        'EllipticCurve\\Utils\\Integer' => __DIR__ . '/..' . '/starkbank/ecdsa/src/utils/integer.php',
        'EllipticCurve\\Utils\\Oid' => __DIR__ . '/..' . '/starkbank/ecdsa/src/utils/oid.php',
        'EllipticCurve\\Utils\\Parser' => __DIR__ . '/..' . '/starkbank/ecdsa/src/utils/der.php',
        'EllipticCurve\\Utils\\Pem' => __DIR__ . '/..' . '/starkbank/ecdsa/src/utils/pem.php',
        'EllipticCurve\\Utils\\TagCode' => __DIR__ . '/..' . '/starkbank/ecdsa/src/utils/der.php',
        'PDF417' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/barcodes/pdf417.php',
        'QRcode' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/barcodes/qrcode.php',
        'SendGrid' => __DIR__ . '/..' . '/sendgrid/sendgrid/lib/SendGrid.php',
        'TCPDF' => __DIR__ . '/..' . '/tecnickcom/tcpdf/tcpdf.php',
        'TCPDF2DBarcode' => __DIR__ . '/..' . '/tecnickcom/tcpdf/tcpdf_barcodes_2d.php',
        'TCPDFBarcode' => __DIR__ . '/..' . '/tecnickcom/tcpdf/tcpdf_barcodes_1d.php',
        'TCPDF_COLORS' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_colors.php',
        'TCPDF_FILTERS' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_filters.php',
        'TCPDF_FONTS' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_fonts.php',
        'TCPDF_FONT_DATA' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_font_data.php',
        'TCPDF_IMAGES' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_images.php',
        'TCPDF_STATIC' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_static.php',
        'TwilioEmail' => __DIR__ . '/..' . '/sendgrid/sendgrid/lib/TwilioEmail.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2185d2f99bcd56787481d9357a5972d3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2185d2f99bcd56787481d9357a5972d3::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2185d2f99bcd56787481d9357a5972d3::$classMap;

        }, null, ClassLoader::class);
    }
}
