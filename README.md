### Important:
Default admin account is ``Wetfish Online`` and the password is ``changeme``. **change it.**

## How do I deploy this container stack?

See [https://github.com/wetfish/production-manifests](https://github.com/wetfish/production-manifests)
for production deployment and full stack dev env info.

For development, to run just this stack, do 
```bash
$EDITOR /etc/hosts
# set an entry like
# 127.0.0.1 wetfishonline.com.local

# setup env files
cp mariadb.env.example mariadb.env
# -> edit, change passwords and other info as needed
cp php.env.example php.env
# -> edit, change passwords to match

# ensure www-data user inside container can write to persistent storage
sudo chown -R 33:33 storage/fish

# bring up the stack
docker compose \
  -f docker-compose.dev.yml \
  up -d \
  --build \
  --force-recreate

# and tail logs
docker compose -f docker-compose.dev.yml logs -f
```

The service will be available at [http://wetfishonline.com.local:2404/forum](http://wetfishonline.com.local:2404/forum)

## When do I need to rebuild the container?

As a dev, only if you edit the Dockerfiles. \
In dev envs the application code is mounted in.
