#!/usr/bin/env bash

function create_auth() {
  local ROOT_DIR="${1}";
  local TEMPLATE_DIR="${2}";

  if [[ -z "${GITHUB_OAUTH}" ]]; then
    printf "No env var GITHUB_OAUTH set. Please visit https://github.com/settings/tokens/new?scopes=&description=Barney-Tech-Test to gain a token \n"
    read -s -p "Please enter the GitHub OAuth Token: " GITHUB_OAUTH; echo "${GITHUB_OAUTH}";
  fi

  printf "Adding GitHub Token %s to files in %s\n" "${GITHUB_OAUTH}" "${ROOT_DIR}";

  docker run --name auth --rm -v "${ROOT_DIR}":/app -i -e GITHUB_OAUTH="${GITHUB_OAUTH}" hairyhenderson/gomplate:stable \
   --input-dir="/app/${TEMPLATE_DIR}" \
   --output-map='/app/{{ .in | strings.ReplaceAll ".tmpl" "" }}';
}

create_auth "${1}" "${2:-dev/docker/services/app/templates}"
