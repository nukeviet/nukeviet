#!/bin/bash

set +o history

SOURCE="${BASH_SOURCE[0]}"
while [ -h "$SOURCE" ]; do
  TARGET="$(readlink "$SOURCE")"
  if [[ $TARGET == /* ]]; then
    SOURCE="$TARGET"
  else
    DIR="$(dirname "$SOURCE")"
    SOURCE="$DIR/$TARGET"
  fi
done
DIR="$(cd -P "$(dirname "$SOURCE")" >/dev/null 2>&1 && pwd)"
cd "$DIR/.."
DIR_PATH=$PWD

if [ ! -f "$DIR_PATH/.env" ]; then
  read -p "Not found .env file in $DIR_PATH"
  exit 1
fi

EnvContent=$(cat "$DIR_PATH/.env")

echo "Begin cleaning up the repo..."

git clean -fdx
rm -rf "$DIR_PATH/vendor"
rm -rf "$DIR_PATH/node_modules"

echo "${EnvContent}" >"$DIR_PATH/.env"

# Khởi chạy selenium
LOGFILE=$(mktemp)
SELENIUM_CMD="selenium-standalone start"

cleanup() {
  echo "Stopping selenium..."
  if kill -0 $SELENIUM_PID 2>/dev/null; then
    kill $SELENIUM_PID
    wait $SELENIUM_PID
  fi
  rm -f "$LOGFILE"
}
trap cleanup EXIT INT TERM

echo "Starting selenium..."
$SELENIUM_CMD >"$LOGFILE" 2>&1 &
SELENIUM_PID=$!

FOUND=""
for i in {1..10}; do
  sleep 1
  if grep -q "Selenium started" "$LOGFILE"; then
    FOUND="yes"
    break
  fi
done

if [ "$FOUND" != "yes" ]; then
  echo "❌ Selenium failed to start within 10 seconds."
  echo "👉 Log output:"
  echo "----------------------"
  cat "$LOGFILE"
  echo "----------------------"
  read -p "Error! Press any key to continue..."
  exit 1
fi

if ! kill -0 $SELENIUM_PID 2>/dev/null; then
  echo "Selenium exited before"
  wait $SELENIUM_PID
  EXIT_CODE=$?
  if [ $EXIT_CODE -ne 0 ]; then
    read -p "Selenium exited with code: $EXIT_CODE"
    exit $EXIT_CODE
  fi
fi

echo "Re-installing composer dependencies..."
composer install

echo "Re-installing npm dependencies..."
npm install

echo "Setting up NukeViet..."
php $DIR_PATH/vendor/bin/codecept run -g install-only

read -p "Finish! Press any key to continue..."
