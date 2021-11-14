BROWSER=chromium

all: foreground_webserver browser watch

foreground_webserver:
	cd build_local && python3 -m http.server &

watch: build
	@while inotifywait -qqre modify "./src" "./content" "bootstrap.php" "config.php" "helpers.php" "config.production.php"; do \
  		date; \
        make build; \
    done

build:
	./vendor/bin/jigsaw build -vvvv

build@prod:
	./vendor/bin/jigsaw build production -vvvv

browser:
	${BROWSER} http://localhost:8000 &
