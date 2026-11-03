import readline from 'node:readline'

const options = process.argv.slice(2)
const timeoutSeconds = 30

if (options.length === 0) process.exit(1)

let selected = 0
let remaining = timeoutSeconds
let rendered = false
let finished = false
let counter
let timeout

function finish(choice = 0, timedOut = false) {
  if (finished) return
  finished = true
  clearInterval(counter)
  clearTimeout(timeout)

  if (process.stdin.isTTY) process.stdin.setRawMode(false)
  process.stdin.pause()
  if (rendered) {
    process.stderr.write('\x1b[2K\r')
    process.stderr.write(timedOut ? 'No answer received; selecting patch.\n' : '\n')
    process.stderr.write('\x1b[?25h')
  }
  process.stdout.write(String(choice + 1))
}

function render() {
  if (rendered) process.stderr.write(`\x1b[${options.length + 1}A`)

  options.forEach((label, index) => {
    const bullet = index === selected ? '●' : '○'
    const marker = index === selected ? '\x1b[1;36m' : ''
    const reset = index === selected ? '\x1b[0m' : ''
    process.stderr.write(`\x1b[2K\r${marker}  ${bullet} ${label}${reset}\n`)
  })
  process.stderr.write(`\x1b[2K\rUse ↑/↓ and Enter (patch in ${remaining}s)\n`)
  rendered = true
}

if (process.stdin.isTTY && process.stderr.isTTY) {
  process.stderr.write('\x1b[?25l')
  readline.emitKeypressEvents(process.stdin)
  process.stdin.setRawMode(true)
  process.stdin.resume()
  process.stdin.on('keypress', (character, key) => {
    if (key?.ctrl && key.name === 'c') {
      process.stderr.write('\x1b[?25h')
      process.exit(130)
    }
    if (key?.name === 'up') selected = (selected - 1 + options.length) % options.length
    else if (key?.name === 'down') selected = (selected + 1) % options.length
    else if (key?.name === 'return') return finish(selected)
    else if (/^[1-9]$/.test(character) && Number(character) <= options.length) return finish(Number(character) - 1)
    else return
    render()
  })
  render()
} else {
  const rl = readline.createInterface({ input: process.stdin })
  process.stderr.write(`Choice [1-${options.length}] (defaults to patch in ${timeoutSeconds}s): `)
  rl.once('line', answer => {
    const choice = Number.parseInt(answer.trim(), 10)
    rl.close()
    finish(choice >= 1 && choice <= options.length ? choice - 1 : 0)
  })
}

counter = setInterval(() => {
  remaining -= 1
  if (process.stdin.isTTY && process.stderr.isTTY) render()
}, 1000)
timeout = setTimeout(() => finish(0, true), timeoutSeconds * 1000)

