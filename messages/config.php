<?php

return [
    // string, required, root directory of all source files
    'sourcePath' => __DIR__.'/..',
    // string, required, root directory containing message translations.
    'messagePath' => __DIR__,
    'languages' => ['lv'],
    'translator' => 'Yii::t',
    // boolean, whether to sort messages by keys when merging new messages
    // with the existing ones. Defaults to false, which means the new (untranslated)
    // messages will be separated from the old (translated) ones.
    'sort' => false,
    // boolean, whether the message file should be overwritten with the merged messages
    'overwrite' => true,
    // boolean, whether to remove messages that no longer appear in the source code.
    // Defaults to false, which means each of these messages will be enclosed with a pair of '@@' marks.
    'removeUnused' => false,
    'except' => [
        '.svn', '.git', '.gitignore', '.gitkeep', '.hgignore', '.hgkeep', '/messages', '/BaseYii.php',
    ],
    'only' => ['*.php'],
    'format' => 'php',
    'ignoreCategories' => [
        'yii',
        'app',
    ],
];
