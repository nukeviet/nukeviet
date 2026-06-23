#!/bin/bash

SOURCE="${BASH_SOURCE[0]}"
while [ -h "$SOURCE" ]; do
  TARGET="$(readlink "$SOURCE")"
  if [[ $TARGET == /* ]]; then
    SOURCE="$TARGET"
  else
    DIR="$( dirname "$SOURCE" )"
    SOURCE="$DIR/$TARGET"
  fi
done
RDIR="$( dirname "$SOURCE" )"
DIR="$( cd -P "$( dirname "$SOURCE" )" >/dev/null 2>&1 && pwd )"

cd "$DIR/vendor/"
DIR_PATH=$PWD

find "$DIR_PATH/" -type f \( -name '*.md' \
  -o -name 'ruleset.xml' \
  -o -name 'CREDITS.txt' \
  -o -name 'composer.json' \
  -o -name 'codecov.yml' \
  -o -name 'composer.lock' \
  -o -name 'package-lock.json' \
  -o -name 'catalog-info.yaml' \
  -o -name 'NOTICE' \
  -o -name 'apigen.neon' \
  -o -name 'THIRD-PARTY-LICENSES' \
  -o -name 'phpunit-integration-cloud-tests.xml' \
  -o -name '.php-cs-fixer.php' \
  -o -name '.php-cs-fixer.dist.php' \
  -o -name '.php_cs' \
  -o -name 'puli.json' \
  -o -name 'format-check.py' \
  -o -name '.github_changelog_generator' \
  -o -name 'phpstan.neon.dist' \
  -o -name '.coveralls.yml' \
  -o -name '.formatter.yml' \
  -o -name '.travis.yml' \
  -o -name 'mkdocs.yml' \
  -o -name '.styleci.yml' \
  -o -name '.pullapprove.yml' \
  -o -name '.htaccess' \
  -o -name '.repo-metadata.json' \
  -o -name '.release-please-manifest.json' \
  -o -name 'bower.json' \
  -o -name 'index.php' \
  -o -name 'build.xml' \
  -o -name 'README.rst' \
  -o -name '.editorconfig' \
  -o -name 'COMMITMENT' \
  -o -name 'COPYING.LESSER' \
  -o -name 'phpword.ini.dist' \
  -o -name 'psalm-baseline.xml' \
  -o -name 'sonar-project.properties' \
  -o -name 'CHANGELOG.TXT' \
  -o -name 'CHANGES.txt' \
  -o -name 'CREDITS' \
  -o -name '.tool-versions' \
  -o -name 'psalm.xml' \
  -o -name 'FUNDING.yml' \
  -o -name 'msgraph-sdk-php.yml' \
  -o -name 'msgraph-sdk-php-core.yml' \
  -o -name 'phpdoc.dist.xml' \
  -o -name 'buildPhar.php' \
  -o -name 'infection.json.dist' \
  -o -name 'infection.json5' \
  -o -name 'phpstan.neon' \
  -o -name '.phpcs.xml.dist' \
  -o -name '.phpcs.xml' \
  -o -name 'phpcs.xml' \
  -o -name 'phpcs.xml.dist' \
  -o -name '.php_cs.dist' \
  -o -name 'phpstan-baseline.neon' \
  -o -name 'phpstan-conditional.php' \
  -o -name 'phpunit10.xml.dist' \
  -o -name '.gitignore' \
  -o -name '.scrutinizer.yml' \
  -o -name 'phpunit.xml' \
  -o -name 'phpunit.integration.xml' \
  -o -name 'CODEOWNERS' \
  -o -name 'Makefile' \
  -o -name 'phpcompatinfo.json' \
  -o -name 'RELEASE' \
  -o -name '.gitattributes' \
  -o -name 'ecs.php' \
  -o -name 'rector.php' \
  -o -name 'run-tests.sh' \
  -o -name 'results.sarif' \
  -o -name 'run-tests-for-all-php-versions.sh' \
  -o -name 'release-please-config.json' \
  -o -name 'roave-bc-check.yaml' \
  -o -name 'TODO.txt' \
  -o -name 'build-phar.sh' \
  -o -name 'get_oauth_token.php' \
  -o -name '.readthedocs.yaml' \
  -o -name 'Dockerfile' \
  -o -name 'opencage_logo_300_150.png' \
  -o -name 'Vagrantfile' \
  -o -name 'phpunit9.xml.dist' \
  -o -name 'phpunit.xml.dist' \) | xargs /bin/rm -f

find "$DIR_PATH/" -type d \( -name 'docs' \
  -o -name 'tmp' \
  -o -name 'Test' \
  -o -name 'test' \
  -o -name 'tests' \
  -o -name '.phive' \
  -o -name '.phpdoc' \
  -o -name '.github' \
  -o -name 'examples' \
  -o -name 'changelog' \
  -o -name 'demo' \
  -o -name 'doc' \
  -o -name 'docs' \
  -o -name 'example' \
  -o -name 'dist' \
  -o -name 'other' \
  -o -name 'guides' \) | xargs /bin/rm -rf

rm -rf "$DIR_PATH/bin"

# Xóa lang không dùng phpmailer
if [ -d "$DIR_PATH/phpmailer/phpmailer/language" ]; then
  for filename in "$DIR_PATH"/phpmailer/phpmailer/language/* ; do
    if [[ ! "$filename" =~ phpmailer\.lang\-(fr|vi|ru|zh)\.php$ ]]; then
      rm -f "$filename"
    fi
  done
fi

# Xóa thư mục thừa thư viện tc-lib-barcode, tc-lib-color
if [ -d "$DIR_PATH/tecnickcom/tc-lib-barcode/resources" ]; then
  rm -rf "$DIR_PATH/tecnickcom/tc-lib-barcode/resources"
fi
if [ -d "$DIR_PATH/tecnickcom/tc-lib-color/resources" ]; then
  rm -rf "$DIR_PATH/tecnickcom/tc-lib-color/resources"
fi

# Thư mục smarty
if [ -d "$DIR_PATH/smarty/smarty/libs" ]; then
  rm -rf "$DIR_PATH/smarty/smarty/libs"
fi

# Tìm tất cả các tệp và thư mục có tên chứa khoảng trắng
find "$DIR_PATH/" -name "* *" -print | while read -r path; do
    echo "Xóa: $path"
    rm -rf "$path"
done

echo "Done!"
