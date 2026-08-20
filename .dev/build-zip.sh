#!/bin/sh
# Build the distributable zips: the theme, and the companion plugin.
#
# WordPress unpacks an update zip and uses its top-level directory as the
# install directory, so the theme zip must contain exactly one root folder
# named `unapp` — which is why this stages into a temp directory rather than
# zipping the working tree in place.
#
# Development material is excluded: .dev (the pattern generator), the docs, the
# child theme and the companion plugin are all part of the repository but none
# of them belongs inside the theme a user installs.
set -e

ROOT=$(cd "$(dirname "$0")/.." && pwd)
VERSION=$(sed -n 's/^Version: *//p' "$ROOT/style.css" | head -1)
OUT=${1:-/tmp/unapp-build}

rm -rf "$OUT"
mkdir -p "$OUT/unapp"

cd "$ROOT"
rsync -a --quiet \
	--exclude '.git' \
	--exclude '.gitignore' \
	--exclude '.dev' \
	--exclude '.claude' \
	--exclude 'CLAUDE.md' \
	--exclude 'docs' \
	--exclude 'child-theme' \
	--exclude 'plugin' \
	--exclude '.DS_Store' \
	--exclude '*.map' \
	--exclude 'node_modules' \
	./ "$OUT/unapp/"

cd "$OUT"
zip -qr "unapp-$VERSION.zip" unapp
rm -rf unapp

# The companion plugin ships separately.
mkdir -p "$OUT/unapp-library"
rsync -a --quiet --exclude '.DS_Store' "$ROOT/plugin/unapp-library/" "$OUT/unapp-library/"
PLUGIN_VERSION=$(sed -n 's/^ \* Version: *//p' "$ROOT/plugin/unapp-library/unapp-library.php" | head -1)
zip -qr "unapp-library-${PLUGIN_VERSION:-1.0.0}.zip" unapp-library
rm -rf unapp-library

echo "theme   $OUT/unapp-$VERSION.zip"
echo "plugin  $OUT/unapp-library-${PLUGIN_VERSION:-1.0.0}.zip"
