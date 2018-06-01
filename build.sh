#/bin/bash

composer dump

rm -rf ib-wpmu-skip-activation.zip
zip -r ib-wpmu-skip-activation.zip ./ -x "*.git*" -x  "*.idea*"

wp plugin install ib-wpmu-skip-activation.zip --activate --force --path=$WP_HOME