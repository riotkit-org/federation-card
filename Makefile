BROWSER=chromium

all: foreground_webserver watch

foreground_webserver:
	cd build_local && python3 -m http.server &

watch:
	${BROWSER} http://localhost:8000 &
	@while inotifywait -qqre modify "./src"; do \
  		date; \
        jigsaw build; \
    done
