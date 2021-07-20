command_exists () {
  command -v "$1" >/dev/null 2>&1
}

# Windows 10, Git Bash and Yarn workaroundif command_exists winpty && test -t 1; thenexec < /dev/tty
fi