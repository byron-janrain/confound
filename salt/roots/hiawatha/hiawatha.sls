hiawathapkg:
  pkg.installed:
    - sources:
      - hiawatha: salt://hiawatha/hiawatha_9.2_amd64.deb
    - require:
      - pkg: ubuntumin

hiawatha:
  service:
    - running
    - require:
      - pkg: hiawathapkg
