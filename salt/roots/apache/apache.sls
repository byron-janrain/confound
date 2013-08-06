apachepkg:
  pkg.installed:
  	- name: apache2
    - require:
    - pkg: ubuntumin

apache2:
  - service: running
  - require:
    - pkg: apachepkg
