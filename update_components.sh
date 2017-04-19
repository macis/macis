#!/usr/bin/env bash
cd "${0%/*}"


for D in *; do
    if [ -d "${D}" ]; then
        echo "*** ${D} ***"   # your processing here

        echo '-- composer --'
        echo "docker run --rm -v $(pwd)/${D}:/app -v ~/.ssh:/root/.ssh composer/composer update -o"
        docker run --rm -v $(pwd)/${D}:/app -v ~/.ssh:/root/.ssh composer/composer update -o


        echo '-- bower --'
        echo "docker run -it --rm  -v $(pwd)/${D}:/data digitallyseamless/nodejs-bower-grunt bower update"
        docker run -it --rm  -v $(pwd)/${D}:/data digitallyseamless/nodejs-bower-grunt bower update

    fi
done



echo 'fin'