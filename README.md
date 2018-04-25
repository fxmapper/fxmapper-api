# fxmapper-api

This repo is for the API served at [api.fxmapper.com/](https://api.fxmapper.com/), NOT the site at [fxmapper.com](https://fxmapper.com).

The two sites share the same database - the migrations are in that repo.

Installation is incredibly basic, again, it just needs database credentials set in the environment; they can be the same credentials as used on the fxmapper site, or (preferably) 
a separate user.

That user will need SELECT priviledges throughout, and INSERT privs on the request_log table only.