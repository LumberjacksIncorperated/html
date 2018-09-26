use DarrynTen\GoogleNaturalLanguagePhp\GoogleNaturalLanguage;

// Config options
$config = [
  'projectId' => 'my-awesome-project'  // At the very least
  ];

  // Make a processor
  $language = new GoogleNaturalLanguage($config);

  // Set text
  $text = 'Google Natural Language processing is awesome!';
  $language->setText($text);

  // Get stuff
  $language->getEntities();
  $language->getSyntax();
  $language->getSentiment();

  // Get all stuff
  $language->getAll();

  // Set optional things
  $language->setType('HTML');
  $language->setLanguage('en');
  $language->setEncodingType('UTF16');

  // Extra features
  $language->setCaching(false);
  $language->setCheapskate(false);

  // Full config options
  $config = [
    'projectId' => 'my-awesome-project',     // required
      'authCache' => \CacheItemPoolInterface,  // stores access tokens
        'authCacheOptions' => $array,            // cache config
          'authHttpHandler' => callable(),         // psr-7 auth handler
            'httpHandler' => callable(),             // psr-7 rest handler
              'keyFile' => $json,                      // content
                'keyFilePath' => $string,                // path
                  'retries' => 3,                          // default is 3
                    'scopes' => $array,                      // app scopes
                      'cache' => $boolean,                     // cache
                        'cheapskate' => $boolean                 // limit text to 1000 chars
                        ];

                        // authCache, authCacheOptions, authHttpHandler and httpHandler are not
                        // currently implemented.
