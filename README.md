array_to_xml
---------------

### Install

```
"konoha/array_to_xml": "*"
```

Then do `composer install`.

### Usage

```php
$xml = new arrayToXml();
$array = [
    [
        'tag' => 'root',
        'elements' => [
            [
                'tag' => 'tag_1',
                'attributes' => [
                    'attr_1' => 'val_1'
                ],
                'content' => 'content_1',
            ],
            [
                'tag' => 'tag_2',
                'content' => 'content_2',
            ]
        ],
    ],
];
echo $xml->load($in)->out();
```