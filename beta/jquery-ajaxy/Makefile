# Javascript/CSS Compressor Makefile - By Benjamin "balupton" Lupton (MIT Licenced)

MAKEFLAGS = --no-print-directory --always-make
MAKE = make $(MAKEFLAGS)

BUILDDIR = ./.build

CLOSUREURL = http://closure-compiler.googlecode.com/files/compiler-latest.zip
CLOSUREDIR = $(BUILDDIR)/closure
CLOSUREFILE = $(CLOSUREDIR)/compiler.jar
YUIURL = http://yuilibrary.com/downloads/yuicompressor/yuicompressor-2.4.2.zip
YUIDIR = $(BUILDDIR)/yui
YUIFILE = $(YUIDIR)/yuicompressor-2.4.2/build/yuicompressor-2.4.2.jar


all:
	$(MAKE) build;
	$(MAKE) add;


demo:
	open ./demo/index.html

add:
	git add .gitignore CHECKLIST.* COPYING.* demo Makefile README.* scripts

push:
	git push --all ; git push --tags ;
	
edithooks:
	mate .git/hooks/pre-commit


refresh:
	wget -q http://github.com/balupton/jquery-sparkle/raw/master/scripts/resources/core.console.js -O scripts/resources/core.console.js ;
	wget -q http://github.com/balupton/jquery-sparkle/raw/master/scripts/resources/core.string.js -O scripts/resources/core.string.js ;
	wget -q http://github.com/balupton/jquery-sparkle/raw/master/scripts/resources/jquery.events.js -O scripts/resources/jquery.events.js ;
	wget -q http://github.com/balupton/jquery-sparkle/raw/master/scripts/resources/jquery.extra.js -O scripts/resources/jquery.extra.js ;
	wget -q http://github.com/balupton/jquery-sparkle/raw/master/scripts/resources/jquery.utilities.js -O scripts/resources/jquery.utilities.js ;
	wget -q http://github.com/balupton/jquery-scrollto/raw/master/scripts/resources/jquery.scrollto.js -O scripts/resources/jquery.scrollto.js ;
	wget -q http://github.com/balupton/jquery-history/raw/master/scripts/resources/jquery.history.js -O scripts/resources/jquery.history.js ;
	wget -q http://github.com/balupton/jquery-history/raw/master/demo/styles/generic.css -O demo/styles/generic.css ;


pack:
	cat \
		./scripts/resources/core.console.js \
		./scripts/resources/core.string.js \
		./scripts/resources/jquery.events.js \
		./scripts/resources/jquery.extra.js \
		./scripts/resources/jquery.utilities.js \
		./scripts/resources/jquery.scrollto.js \
		./scripts/resources/jquery.history.js \
		./scripts/resources/jquery.ajaxy.js \
		> ./scripts/jquery.ajaxy.js;

compress:
	java -jar $(CLOSUREFILE) --create_source_map ./scripts/closure.map --js_output_file=./scripts/jquery.ajaxy.min.js --js=./scripts/jquery.ajaxy.js;
	
build:
	$(MAKE) pack;
	$(MAKE) compress;
	
build-update:
	$(MAKE) clean;
	mkdir $(BUILDDIR) $(CLOSUREDIR) $(YUIDIR);
	cd $(CLOSUREDIR); wget -q $(CLOSUREURL) -O file.zip; tar -xf file.zip;
	cd $(YUIDIR); wget -q $(YUIURL) -O file.zip; tar -xf file.zip;
	
clean:
	rm -Rf $(BUILDDIR);
	