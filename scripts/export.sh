#!/bin/bash

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

# Lấy commit đầu từ
GIT_ID_OLD=$1
if [ -z "$GIT_ID_OLD" ]; then
  # Nhập và lấy commit đầu
  echo -n "Nhập commit từ (không tính commit này): "
  read GIT_ID_OLD
fi
if ! [[ "$GIT_ID_OLD" =~ ^[a-f0-9]+$ ]]; then
  echo "Commit id không đúng cấu trúc"
  exit
fi

# Lấy commit cuối
GIT_ID_NEW=$(git log --format="%H" -n 1)
if [[ $GIT_ID_OLD == $GIT_ID_NEW ]]; then
  echo "Không có thay đổi nào"
  exit
fi

# Xuất các file thay đổi
echo "Xuất file thay đổi:"
rm -rf "${DIR_PATH}/update/"
mkdir -p "${DIR_PATH}/update"

git diff-tree -r --name-only --diff-filter=ACMRT $GIT_ID_OLD $GIT_ID_NEW | xargs tar -rf "${DIR_PATH}/update/update.tar"

cd "$DIR_PATH/update"
tar -xvf update.tar
cd "$DIR_PATH"
rm -f "${DIR_PATH}/update/update.tar"

# Xóa đi các file/thư mục không đưa lên
if [ -d "$DIR_PATH/update/src/assets/freecontent" ]; then
  rm -rf "$DIR_PATH/update/src/assets/freecontent"
fi
if [ -d "$DIR_PATH/update/src/assets/news" ]; then
  rm -rf "$DIR_PATH/update/src/assets/news"
fi
if [ -d "$DIR_PATH/update/src/assets/mobile" ]; then
  rm -rf "$DIR_PATH/update/src/assets/mobile"
fi
if [ -f "$DIR_PATH/update/src/data/config/config_global.php" ]; then
  rm -rf "$DIR_PATH/update/src/data/config/config_global.php"
fi
if [ -f "$DIR_PATH/update/src/data/config/disable_site_content.vi.txt" ]; then
  rm -rf "$DIR_PATH/update/src/data/config/disable_site_content.vi.txt"
fi
if [ -f "$DIR_PATH/update/src/data/config/robots.php" ]; then
  rm -rf "$DIR_PATH/update/src/data/config/robots.php"
fi
if [ -f "$DIR_PATH/update/src/.htaccess" ]; then
  rm -rf "$DIR_PATH/update/src/.htaccess"
fi

# Xuất các file bị xóa
echo ""
echo "Xuất file/thư mục bị xóa:"
git diff-tree -r --name-only --diff-filter=D $GIT_ID_OLD $GIT_ID_NEW >"$DIR_PATH/delete.tmp"
if [ -f "$DIR_PATH/delete.txt" ]; then
  rm -f "$DIR_PATH/delete.txt"
fi

arrFoldersDel=()
DELETE_MODE=$2

for linedelete in $(cat "$DIR_PATH/delete.tmp"); do
  # Chỉ check xóa trong src
  if [[ ! "$linedelete" == src/* ]]; then
    continue
  fi

  if [ ! -f "$DIR_PATH/$linedelete" ]; then
    IFS='/'
    read -ra arrDirs <<<"$linedelete"
    len=${#arrDirs[@]}

    # Duyệt kiểm tra cả thư mục bị xóa
    path="$DIR_PATH"
    pathRelative=""
    len=$((len - 1))
    for ((i = 0; i <= $len; i++)); do
      path="$path/${arrDirs[$i]}"
      if [[ $i -eq 0 ]]; then
        pathRelative="${arrDirs[$i]}"
      else
        pathRelative="$pathRelative/${arrDirs[$i]}"
      fi
      # Thư mục cha xóa rồi thì thôi
      if printf '%s\0' "${arrFoldersDel[@]}" | grep -Fxqz -- "$path"; then
        continue 2
      fi

      if [[ $i -lt $len ]]; then
        # Xóa cả thư mục
        if [ ! -d "$path" ]; then
          echo "D: $pathRelative"

          if [ -z "$DELETE_MODE" ]; then
            echo "rm -rf ./${pathRelative#src/}" >>"$DIR_PATH/delete.txt"
          else
            echo "nv_deletefile(NV_ROOTDIR . '/${pathRelative#src/}');" >>"$DIR_PATH/delete.txt"
          fi

          arrFoldersDel+=("$path")
          continue 2
        fi
      else
        # Xóa file
        echo "F: $linedelete"

        if [ -z "$DELETE_MODE" ]; then
          echo "rm -f ./${linedelete#src/}" >>"$DIR_PATH/delete.txt"
        else
          echo "nv_deletefile(NV_ROOTDIR . '/${linedelete#src/}');" >>"$DIR_PATH/delete.txt"
        fi
      fi
    done
  fi
done

rm -f "$DIR_PATH/delete.tmp"

echo "Xong!"
