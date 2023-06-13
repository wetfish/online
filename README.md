### Important:
Default admin account is ``Wetfish Online`` and the password is ``changeme``. **change it.**

## How do I deploy this container stack?

See [https://github.com/wetfish/production-manifests](https://github.com/wetfish/production-manifests)
for production deployment and full stack dev env info.

For development, to run just this stack, do 
```bash
cp mariadb.env.example mariadb.env
# -> edit, change passwords and other info as needed
cp php.env.example php.env
# -> edit, change passwords to match

docker compose \
  -f docker-compose.dev.yml \
  up -d \
  --build \
  --force-recreate

docker compose -f docker-compose.dev.yml logs -f
```

The service will be available at [http://127.0.0.1:2404](http://127.0.0.1:2404)

## When do I need to rebuild the container?

As a dev, only if you edit the Dockerfiles. \
In dev envs the application code is mounted in.
