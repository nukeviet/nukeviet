# Introduction about NukeViet
NukeViet is the first opensource CMS in Vietnam. The lastest version - NukeViet 5 coding ground up support lastest web technologies, include reponsive web design (use HTML 5, CSS 3, Composer, Smarty), jQuery, Ajax...) enables you to build websites and online applications rapidly.

With it own core libraries built in, NukeViet 5 is cross platforms and frameworks independent. By basic knowledge of PHP and MySQL, you can easily extend NukeViet for your purposes.

NukeViet core is simply but powerful. It supports abstract modules which can be duplicate. So, it helps you create automatically many modules without any line of code from existing abstract modules.

NukeViet supports installing automatically modules, blocks, themes at Admin Control Panel and supports packing features which allow you to share your modules to web- community.

NukeViet fully supports multi-languages for internationalization and localization. Not only multi-interface languages but also multi-database languages are supported. NukeViet supports you to build new languages which are not distributed by NukeViet.

Detailed information about Nukeviet at Wikipedia Encyclopedia: http://vi.wikipedia.org/wiki/NukeViet

## Getting Started

### For users

**Requirements:**  
- OS: Unix (Linux, Ubuntu, Fedora ...) or Windows
- PHP: From PHP 8.2 to PHP 8.4
- MySQL: MySQL 5.5 or newer

**Installation:**

- Download the source code to your computer and unzip it.
- Upload all files and subdirectories in the `src` directory to the to the desired location on your web server:
    - If you want to integrate NukeViet into the root of your domain (e.g. http://example.com/), upload all contents of the unzipped `src` directory into the root directory of your web server.
    - If you want to have your NukeViet installation in its own subdirectory on your website (e.g. http://example.com/subdir/), create the subdir directory on your server and upload the contents of the unzipped `src` directory to the directory via FTP.
- Access the website by internet browser to install:
    - If you installed NukeViet in the root directory, you should visit: http://example.com/
    - If you installed NukeViet in its own subdirectory called subdir, for example, you should visit: http://example.com/subdir/

**Next step:**

Please see the [user manual](https://wiki.nukeviet.vn/nukeviet5) for more information.

### For developers

If you only need to develop modules, themes, blocks, plugins. You just need to read the Getting Started For Users above. If you are planning on core development or testing, please follow this guide:

**Requirements:**  
- Node.JS v18.17+
- NPM v10.5+
- Git
- Webserver is similar to the requirements for the user.
- Composer v2.6+

**Installation:**

Get the source code:

```bash
git clone https://github.com/nukeviet/nukeviet.git
cd nukeviet
git checkout nukeviet5.0
```

Install the necessary libraries:

```bash
npm install
composer install
```

Prepare Selenium server:

```bash
npm install selenium-standalone -g
selenium-standalone install
```

Launch this command in a separate terminal and keep it running:

```bash
selenium-standalone start
```

Prepare the web server with the webroot pointing to the `src` directory. Copy the .env.example file to .env, then open it and fill in all the necessary information.

If you want to run the full test suite, use the command.

```bash
php vendor/bin/codecept run
```

If you want to quickly test the PHP unit tests, use the command. With unit tests, it is required that you have successfully set up the website beforehand.

```bash
php vendor/bin/codecept run Unit
```

If you want to test according to specific needs, use the command:

```bash
php vendor/bin/codecept run -g install
php vendor/bin/codecept run -g users
php vendor/bin/codecept run -g all
...
```

We offer different groups of needs as follows:

- `install` serving system installation tasks.
- `install-only` only test the installation.

The groups below require the website to be installed:

- `users` functional groups for logged-in users.
- `stat` testing the access statistics feature.
- `sendmail` testing the email sending function.
- `smtp` enable and configure the SMTP email sending function.
- `off-mail` disable email sending.
- `all` test everything.

**Next step:**

Please see the [technical manual](https://wiki.nukeviet.vn/technical_manual5) for more information.

## Licensing
NukeViet is released under GNU/GPL version 2 or any later version.

See [LICENSE](LICENSE) for the full license.

## NukeViet official website
  - Home page - link to all resources NukeViet: http://nukeviet.vn (select Vietnamese to have the latest information).
  - NukeViet official Coding: http://code.nukeviet.vn
  - Theme, Module and more add-ons for NukeViet: http://nukeviet.vn/vi/store/
  - NukeViet official Forum http://forum.nukeviet.vn/
  - Open Document Library for NukeViet: http://wiki.nukeviet.vn/
  - NukeViet Translate Center: http://translate.nukeviet.vn/
  - NukeViet partner: http://nukeviet.vn/vi/partner/
  - NukeViet Education Center: http://nukeviet.edu.vn
  - NukeViet SaaS: http://nukeviet.com (testing)

## Community
  - NukeViet Fanpage: http://facebook.com/nukeviet
  - NukeViet group on FB: https://www.facebook.com/groups/nukeviet/
  - http://twitter.com/nukeviet
  - https://groups.google.com/forum/?fromgroups#!forum/nukeviet
  - https://www.youtube.com/c/nukeviet



## NukeViet Centre for Research and Development
VIETNAM OPEN SOURCE DEVELOPMENT JOINT STOCK COMPANY (VINADES.,JSC)

Website: http://vinades.vn | http://nukeviet.vn | http://nukeviet.com

Head Office:
  - 6th floor, Song Da building, No. 131 Tran Phu street, Van Quan ward, Ha Dong district, Hanoi city, Vietnam.
  - Phone: +84-24-85872007, Fax: +84-24-35500914, Email: contact (at) vinades.vn
