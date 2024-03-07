<?php

namespace CarlBennett\API\Libraries\Core;

class ColorNames
{
    protected $colorMap;

    public function __construct()
    {
      $this->addColor('aliceblue', 'f0f8ff');
      $this->addColor('antiquewhite', 'faebd7');
      $this->addColor('aqua', '00ffff');
      $this->addColor('aquamarine', '7fffd4');
      $this->addColor('azure', 'f0ffff');
      $this->addColor('beige', 'f5f5dc');
      $this->addColor('bisque', 'ffe4c4');
      $this->addColor('black', '000000');
      $this->addColor('blanchedalmond', 'ffebcd');
      $this->addColor('blue', '0000ff');
      $this->addColor('blueviolet', '8a2be2');
      $this->addColor('brown', 'a52a2a');
      $this->addColor('burlywood', 'deb887');
      $this->addColor('cadetblue', '5f9ea0');
      $this->addColor('chartreuse', '7fff00');
      $this->addColor('chocolate', 'd2691e');
      $this->addColor('coral', 'ff7f50');
      $this->addColor('cornflowerblue', '6495ed');
      $this->addColor('cornsilk', 'fff8dc');
      $this->addColor('crimson', 'dc143c');
      $this->addColor('cyan', '00ffff');
      $this->addColor('darkblue', '00008b');
      $this->addColor('darkcyan', '008b8b');
      $this->addColor('darkgoldenrod', 'b8860b');
      $this->addColor('darkgray', 'a9a9a9');
      $this->addColor('darkgreen', '006400');
      $this->addColor('darkkhaki', 'bdb76b');
      $this->addColor('darkmagenta', '8b008b');
      $this->addColor('darkolivegreen', '556b2f');
      $this->addColor('darkorange', 'ff8c00');
      $this->addColor('darkorchid', '9932cc');
      $this->addColor('darkred', '8b0000');
      $this->addColor('darksalmon', 'e9967a');
      $this->addColor('darkseagreen', '8fbc8f');
      $this->addColor('darkslateblue', '483d8b');
      $this->addColor('darkslategray', '2f4f4f');
      $this->addColor('darkturquoise', '00ced1');
      $this->addColor('darkviolet', '9400d3');
      $this->addColor('deeppink', 'ff1493');
      $this->addColor('deepskyblue', '00bfff');
      $this->addColor('dimgray', '696969');
      $this->addColor('dodgerblue', '1e90ff');
      $this->addColor('firebrick', 'b22222');
      $this->addColor('floralwhite', 'fffaf0');
      $this->addColor('forestgreen', '228b22');
      $this->addColor('fuchsia', 'ff00ff');
      $this->addColor('gainsboro', 'dcdcdc');
      $this->addColor('ghostwhite', 'f8f8ff');
      $this->addColor('gold', 'ffd700');
      $this->addColor('goldenrod', 'daa520');
      $this->addColor('gray', '808080');
      $this->addColor('green', '008000');
      $this->addColor('greenyellow', 'adff2f');
      $this->addColor('honeydew', 'f0fff0');
      $this->addColor('hotpink', 'ff69b4');
      $this->addColor('indianred', 'cd5c5c');
      $this->addColor('indigo', '4b0082');
      $this->addColor('ivory', 'fffff0');
      $this->addColor('khaki', 'f0e68c');
      $this->addColor('lavender', 'e6e6fa');
      $this->addColor('lavenderblush', 'fff0f5');
      $this->addColor('lawngreen', '7cfc00');
      $this->addColor('lemonchiffon', 'fffacd');
      $this->addColor('lightblue', 'add8e6');
      $this->addColor('lightcoral', 'f08080');
      $this->addColor('lightcyan', 'e0ffff');
      $this->addColor('lightgoldenrodyellow', 'fafad2');
      $this->addColor('lightgray', 'd3d3d3');
      $this->addColor('lightgreen', '90ee90');
      $this->addColor('lightpink', 'ffb6c1');
      $this->addColor('lightsalmon', 'ffa07a');
      $this->addColor('lightseagreen', '20b2aa');
      $this->addColor('lightskyblue', '87cefa');
      $this->addColor('lightslategray', '778899');
      $this->addColor('lightsteelblue', 'b0c4de');
      $this->addColor('lightyellow', 'ffffe0');
      $this->addColor('lime', '00ff00');
      $this->addColor('limegreen', '32cd32');
      $this->addColor('linen', 'faf0e6');
      $this->addColor('magenta', 'ff00ff');
      $this->addColor('maroon', '800000');
      $this->addColor('mediumaquamarine', '66cdaa');
      $this->addColor('mediumblue', '0000cd');
      $this->addColor('mediumorchid', 'ba55d3');
      $this->addColor('mediumpurple', '9370db');
      $this->addColor('mediumseagreen', '3cb371');
      $this->addColor('mediumslateblue', '7b68ee');
      $this->addColor('mediumspringgreen', '00fa9a');
      $this->addColor('mediumturquoise', '48d1cc');
      $this->addColor('mediumvioletred', 'c71585');
      $this->addColor('midnightblue', '191970');
      $this->addColor('mintcream', 'f5fffa');
      $this->addColor('mistyrose', 'ffe4e1');
      $this->addColor('moccasin', 'ffe4b5');
      $this->addColor('navajowhite', 'ffdead');
      $this->addColor('navy', '000080');
      $this->addColor('oldlace', 'fdf5e6');
      $this->addColor('olive', '808000');
      $this->addColor('olivedrab', '6b8e23');
      $this->addColor('orange', 'ffa500');
      $this->addColor('orangered', 'ff4500');
      $this->addColor('orchid', 'da70d6');
      $this->addColor('palegoldenrod', 'eee8aa');
      $this->addColor('palegreen', '98fb98');
      $this->addColor('paleturquoise', 'afeeee');
      $this->addColor('palevioletred', 'db7093');
      $this->addColor('papayawhip', 'ffefd5');
      $this->addColor('peachpuff', 'ffdab9');
      $this->addColor('peru', 'cd853f');
      $this->addColor('pink', 'ffc0cb');
      $this->addColor('plum', 'dda0dd');
      $this->addColor('powderblue', 'b0e0e6');
      $this->addColor('purple', '800080');
      $this->addColor('red', 'ff0000');
      $this->addColor('rosybrown', 'bc8f8f');
      $this->addColor('royalblue', '4169e1');
      $this->addColor('saddlebrown', '8b4513');
      $this->addColor('salmon', 'fa8072');
      $this->addColor('sandybrown', 'f4a460');
      $this->addColor('seagreen', '2e8b57');
      $this->addColor('seashell', 'fff5ee');
      $this->addColor('sienna', 'a0522d');
      $this->addColor('silver', 'c0c0c0');
      $this->addColor('skyblue', '87ceeb');
      $this->addColor('slateblue', '6a5acd');
      $this->addColor('slategray', '708090');
      $this->addColor('snow', 'fffafa');
      $this->addColor('springgreen', '00ff7f');
      $this->addColor('steelblue', '4682b4');
      $this->addColor('tan', 'd2b48c');
      $this->addColor('teal', '008080');
      $this->addColor('thistle', 'd8bfd8');
      $this->addColor('tomato', 'ff6347');
      $this->addColor('turquoise', '40e0d0');
      $this->addColor('violet', 'ee82ee');
      $this->addColor('wheat', 'f5deb3');
      $this->addColor('white', 'ffffff');
      $this->addColor('whitesmoke', 'f5f5f5');
      $this->addColor('yellow', 'ffff00');
      $this->addColor('yellowgreen', '9acd32');
    }

    public function addColor(string $name, string $hex): void
    {
        $this->colorMap[$name] = $hex;
    }

    public function colorLookupName(string $name): string|false
    {
        return !isset($this->colorMap[$name]) ? false : $this->colorMap[$name];
    }

    public function colorLookupHex(string $hex): string|false
    {
        foreach ($this->colorMap as $key => $val)
        {
            if ($val == $hex) return $key;
        }
        return false;
    }

    public function colorMatchName(string $value): array|false
    {
        $haystack = \str_replace(' ', '', \trim(\strtolower($value)));
        foreach ($this->colorMap as $key => $val)
        {
            if ($haystack != $key && \stripos($haystack, '#' . $key) === false)
            {
                continue;
            }
            return ['name' => $key, 'hex' => $val];
        }
        return false;
    }

    public function removeColor($name): bool
    {
        if (!isset($this->colorMap[$name]))
        {
          return false;
        }

        unset($this->colorMap[$name]);
        return true;
    }
}
