<h1 align="center">
    vladpak1\TooSimpleQR
</h1>

**TooSimpleQR** это обертка над чудесным [chillerlan/php-qrcode](https://github.com/chillerlan/php-qrcode), которая
упрощает генерацию QR кодов, уделяя больше внимания внешним кастомизациям и простоте интерфейса.
Это мой небольшой пет-проект, который очень далек от идеала, так что используйте на свой страх и риск.

Если вы хотите генерировать QR коды в серьезном проекте, лучше
используйте [chillerlan/php-qrcode](https://github.com/chillerlan/php-qrcode).

Примеры созданных с помощью этой библиотеки QR кодов:
<p align="center">
<img src="https://i.postimg.cc/HxrNR9tc/presets.png" alt="">
</p>

## Требования

- PHP 8.0+
- Composer
- GD или Imagick

## Установка

```bash
composer require vladpak1/too-simple-qr
```

## Пример использования

```php

use vladpak1\TooSimpleQR\QRFactory;
use vladpak1\TooSimpleQR\OutputConstants;
use vladpak1\TooSimpleQR\Image\Logo;

// Загружаем composer
require_once __DIR__ . '/vendor/autoload.php';

$qr = \vladpak1\TooSimpleQR\QRFactory::QRCode();

$settings = \vladpak1\TooSimpleQR\QRFactory::QRSettings();

$logo = new Logo();
$logo->setByPath(Logo::TWITTER_LOGO);

$settings
    ->setOutput(OutputConstants::OUTPUT_PNG)
    ->setSize(500)                              // Размер в пикселях.
    ->setBackgroundColor('#ffffff')             // Цвет фона в формате HEX.
    ->setMargin(50)                             // Отступ от краев в пикселях.
    ->setFinderColor('#1d9bf0')                 // Цвет файндеров в формате HEX.
    ->setDataColor('#3284bc')                   // Цвет данных в формате HEX.
    ->setCorrectionLevel(OutputConstants::CORRECTION_LEVEL_H) // Уровень коррекции ошибок.
    ->setCircularModules(true)                  // Округлять модули.
    ->setCircleRadius(0.75)                     // Радиус окружности.
    ->setLogo($logo)                            // Логотип.
    ->setCustomFinderShape(OutputConstants::SHAPE_CIRCLE); // Форма файндеров.

$qrCodeImage = $qr
    ->setData('Hello World!')
    ->setSettings($settings)
    ->render();
    
// Теперь мы можем сохранить или сразу вывести (как HTML) QR код.
$qrCodeImage->echo();

```

В результате мы получим:
<p align="center">
<img src="https://i.postimg.cc/MK1pgysF/example-1.png" alt="">
</p>

Также мы можем изуродовать этот QR код, добавив, например градиент:

```php
->setBackgroundGradient('#FFD8D8', '#FFF9F9', Gradient::DIRECTION_BOTTOM_TO_TOP);
```

<p align="center">
<img src="https://i.postimg.cc/cHv2gG65/12.png" alt="">
</p>

## Нужно быстро сгенерировать QR код?

Воспользуйтесь пресетами — заранее подготовленными настройками для генерации QR кода.

Вы также легко можете создавать свои пресеты, наследуясь от абстрактного
класса `vladpak1\TooSimpleQR\Preset\AbstractPreset`.

Пример использования пресета:

```php

$preset = new \vladpak1\TooSimpleQR\Preset\Presets\InstagramPreset('Hello World!');

// Вы также можете задать драйвер и интерфейс вывода для пресета.
$preset
    ->setDriver(\vladpak1\TooSimpleQR\OutputConstants::DRIVER_GD)
    ->setOutput(\vladpak1\TooSimpleQR\OutputConstants::OUTPUT_PNG);
    
// Мы получили брендированный QR код Instagram.
$preset->render()->save('path/to/qr.png');


```

## GD или Imagick?

Эта библиотека будет работать с любым из них, но некоторые настройки поддерживаются только Imagick.
Вероятно, в будущем я приведу все к более консистентному виду и разница драйверов не будет так заметна.
Однако на данный момент рекомендуется использования Imagick.

Драйвер для использования определяется автоматически в зависимости от того, какой из них установлен на вашем сервере,
отдавая предпочтение Imagick.

Но вы также можете принудительно указать драйвер:

```php
$qr->setDriver(OutputConstants::DRIVER_GD);
```

## Тестирование

TooSimpleQR использует [PHPUnit](https://github.com/sebastianbergmann/phpunit) для тестирования.
На данный момент тестов практически нет в основном из-за ошибок в архитектуре пакета, которые я буду стараться
исправлять.

Для запуска тестов используйте:

```bash
composer test
```

## Лицензия

TooSimpleQR распространяется под лицензией MIT.