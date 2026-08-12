#!/usr/bin/env bash
# toggle-maintenance.sh — switch the online forum between normal and maintenance mode.
#
# Usage:
#   ./toggle-maintenance.sh on     # serve the "under construction" notice
#   ./toggle-maintenance.sh off    # restore the normal forum
#
# Swaps the nginx config mount in docker-compose.yml and recreates online-web.

set -euo pipefail

COMPOSE_FILE="${COMPOSE_FILE:-docker-compose.yml}"
NORMAL="config/nginx.conf:/etc/nginx/conf.d/default.conf:ro"
MAINT="config/nginx-maintenance.conf:/etc/nginx/conf.d/default.conf:ro"

usage() {
  echo "Usage: $0 [on|off]" >&2
  exit 1
}

switch_to() {
  local from="$1" to="$2" label="$3"

  if ! grep -qF "$to" "$COMPOSE_FILE"; then
    if grep -qF "$from" "$COMPOSE_FILE"; then
      sed -i.bak "s#${from}#${to}#" "$COMPOSE_FILE"
    else
      echo "ERROR: could not find nginx config mount in $COMPOSE_FILE" >&2
      exit 1
    fi
  fi

  docker compose up -d online-web
  echo "Maintenance mode: $label"
}

case "${1:-}" in
  on)  switch_to "$NORMAL" "$MAINT" "ON" ;;
  off) switch_to "$MAINT" "$NORMAL" "OFF" ;;
  *)   usage ;;
esac
