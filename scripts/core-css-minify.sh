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
DIR="$( cd -P "$( dirname "$SOURCE" )" >/dev/null 2>&1 && pwd )"
cd "$DIR/.."
DIR_PATH=$PWD

# Minify CSS
find "${DIR_PATH}/src/assets/css" -name "core.*.css" ! -name "*.min.css" -exec sh -c 'cleancss -O1 --format breakWith=lf --format "keepSpecialComments: *" --with-rebase --source-map --source-map-inline-sources --output "${0%.css}.min.css" "$0"' {} \;

# Remove non-minified CSS
find "${DIR_PATH}/src/assets/css" -type f \( \
  -name 'core.*.css' ! -name 'core.*.min.css' -o \
  -name 'core.*.css.map' ! -name 'core.*.min.css.map' \
\) -exec rm -f {} +

# Xuống dòng banner
find "${DIR_PATH}/src/assets/css" -type f -name "core.*.css" -exec sed -i 's|\*/|\*/\n|g' {} +
