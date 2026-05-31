const { app, BrowserWindow } = require('electron')
const { spawn } = require('child_process')
const fs = require('fs')
const http = require('http')
const path = require('path')

const APP_ID = 'com.pos.mobile'
const APP_URL = 'http://127.0.0.1:8000'
const APP_HOST = '127.0.0.1'
const APP_PORT = 8000
const PROJECT_ROOT = path.resolve(__dirname)
const DEFAULT_PHP_BINARY = 'E:\\php\\php.exe'
const SERVER_READY_TIMEOUT_MS = 30000

let mainWindow = null
let phpProcess = null
let shuttingDown = false

function getPhpBinary() {
    if (process.env.PHP_BINARY) {
        return process.env.PHP_BINARY
    }

    if (app.isPackaged) {
        return path.join(process.resourcesPath, 'php', 'php.exe')
    }

    return DEFAULT_PHP_BINARY
}

function getBundledDatabasePath() {
    return path.join(PROJECT_ROOT, 'database', 'database.sqlite')
}

function getPersistentDatabasePath() {
    return path.join(app.getPath('userData'), 'database.sqlite')
}

function getPublicStoragePath() {
    return path.join(PROJECT_ROOT, 'public', 'storage')
}

function getStorageSourcePath() {
    return path.join(PROJECT_ROOT, 'storage', 'app', 'public')
}

function delay(milliseconds) {
    return new Promise((resolve) => setTimeout(resolve, milliseconds))
}

function createLoadingPage(message) {
    return `
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1" />
            <title>POS Mobile</title>
            <style>
                :root {
                    color-scheme: dark;
                }
                body {
                    margin: 0;
                    min-height: 100vh;
                    display: grid;
                    place-items: center;
                    background: radial-gradient(circle at top, #1f2937 0%, #0f172a 55%, #020617 100%);
                    color: #e5e7eb;
                    font-family: Arial, Helvetica, sans-serif;
                }
                .card {
                    width: min(560px, calc(100vw - 48px));
                    padding: 32px;
                    border-radius: 20px;
                    background: rgba(15, 23, 42, 0.84);
                    border: 1px solid rgba(148, 163, 184, 0.2);
                    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.35);
                    backdrop-filter: blur(12px);
                    text-align: center;
                }
                .spinner {
                    width: 56px;
                    height: 56px;
                    border-radius: 50%;
                    border: 5px solid rgba(148, 163, 184, 0.25);
                    border-top-color: #38bdf8;
                    animation: spin 1s linear infinite;
                    margin: 0 auto 20px;
                }
                h1 {
                    margin: 0 0 8px;
                    font-size: 24px;
                }
                p {
                    margin: 0;
                    color: #cbd5e1;
                    line-height: 1.6;
                }
                code {
                    color: #7dd3fc;
                }
                @keyframes spin {
                    to { transform: rotate(360deg); }
                }
            </style>
        </head>
        <body>
            <div class="card">
                <div class="spinner"></div>
                <h1>Starting POS Mobile</h1>
                <p>${message}</p>
                <p><code>${APP_URL}</code></p>
            </div>
        </body>
        </html>
    `
}

function createErrorPage(title, message) {
    return `
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1" />
            <title>POS Mobile</title>
            <style>
                :root {
                    color-scheme: dark;
                }
                body {
                    margin: 0;
                    min-height: 100vh;
                    display: grid;
                    place-items: center;
                    background: linear-gradient(135deg, #111827 0%, #030712 100%);
                    color: #f9fafb;
                    font-family: Arial, Helvetica, sans-serif;
                }
                .card {
                    width: min(760px, calc(100vw - 48px));
                    padding: 32px;
                    border-radius: 18px;
                    background: #111827;
                    border: 1px solid rgba(248, 113, 113, 0.35);
                    box-shadow: 0 24px 80px rgba(0, 0, 0, 0.45);
                }
                h1 {
                    margin: 0 0 12px;
                    font-size: 26px;
                }
                p {
                    margin: 0 0 16px;
                    color: #d1d5db;
                    line-height: 1.6;
                }
                pre {
                    margin: 0;
                    padding: 16px;
                    border-radius: 12px;
                    background: #030712;
                    color: #fca5a5;
                    white-space: pre-wrap;
                    word-break: break-word;
                }
            </style>
        </head>
        <body>
            <div class="card">
                <h1>${title}</h1>
                <p>Please check the Laravel and PHP startup logs.</p>
                <pre>${message}</pre>
            </div>
        </body>
        </html>
    `
}

function loadHtml(html) {
    if (!mainWindow || mainWindow.isDestroyed()) {
        return Promise.resolve()
    }

    return mainWindow.loadURL(`data:text/html;charset=utf-8,${encodeURIComponent(html)}`)
}

function createWindow() {
    mainWindow = new BrowserWindow({
        width: 1280,
        height: 800,
        show: false,
        backgroundColor: '#0f172a',
        webPreferences: {
            contextIsolation: true,
            nodeIntegration: false
        }
    })

    mainWindow.on('closed', () => {
        mainWindow = null
    })

    mainWindow.once('ready-to-show', () => {
        if (mainWindow) {
            mainWindow.show()
        }
    })

    mainWindow.webContents.on('did-fail-load', async (_event, errorCode, errorDescription, validatedURL, isMainFrame) => {
        if (!isMainFrame || !mainWindow || mainWindow.isDestroyed()) {
            return
        }

        if (validatedURL !== APP_URL) {
            return
        }

        const message = `Failed to load ${validatedURL}: ${errorDescription} (${errorCode})`
        console.error(message)

        try {
            await loadHtml(createErrorPage('Unable to open the POS app', message))
        } catch (fallbackError) {
            console.error('Failed to display the fallback error page:', fallbackError)
        }
    })

    if (!app.isPackaged) {
        mainWindow.webContents.openDevTools({ mode: 'detach' })
    }

    return mainWindow
}

function ensureDatabaseFile() {
    const bundledDatabase = getBundledDatabasePath()
    const persistentDatabase = getPersistentDatabasePath()

    fs.mkdirSync(path.dirname(persistentDatabase), { recursive: true })

    if (!fs.existsSync(persistentDatabase)) {
        if (fs.existsSync(bundledDatabase)) {
            fs.copyFileSync(bundledDatabase, persistentDatabase)
        } else {
            fs.writeFileSync(persistentDatabase, '')
        }
    }

    return persistentDatabase
}

function ensureStorageLink() {
    const storageSource = getStorageSourcePath()
    const storageLink = getPublicStoragePath()

    fs.mkdirSync(storageSource, { recursive: true })
    fs.mkdirSync(path.dirname(storageLink), { recursive: true })

    if (fs.existsSync(storageLink)) {
        try {
            const stats = fs.lstatSync(storageLink)
            if (stats.isSymbolicLink()) {
                return
            }

            const entries = fs.readdirSync(storageLink)
            if (entries.length === 0) {
                fs.rmSync(storageLink, { recursive: true, force: true })
                fs.symlinkSync(storageSource, storageLink, process.platform === 'win32' ? 'junction' : 'dir')
                return
            }

            console.warn('public/storage already exists as a real directory. Leaving it in place.')
            return
        } catch (error) {
            console.warn('Could not inspect the storage link:', error)
        }
    } else {
        try {
            fs.symlinkSync(storageSource, storageLink, process.platform === 'win32' ? 'junction' : 'dir')
            return
        } catch (error) {
            console.warn('Could not create the storage link:', error)
        }
    }
}

function isServerReady() {
    return new Promise((resolve) => {
        const request = http.get(`${APP_URL}/`, (response) => {
            response.resume()
            resolve(true)
        })

        request.on('error', () => resolve(false))
        request.setTimeout(1000, () => {
            request.destroy()
            resolve(false)
        })
    })
}

async function waitForServerReady() {
    const deadline = Date.now() + SERVER_READY_TIMEOUT_MS

    while (Date.now() < deadline) {
        if (await isServerReady()) {
            return true
        }

        await delay(1000)
    }

    return false
}

function spawnPhpServer(databasePath) {
    if (phpProcess && !phpProcess.killed) {
        return phpProcess
    }

    const phpBinary = getPhpBinary()
    const artisanPath = path.join(PROJECT_ROOT, 'artisan')

    if (!fs.existsSync(phpBinary)) {
        throw new Error(`PHP binary not found: ${phpBinary}`)
    }

    if (!fs.existsSync(artisanPath)) {
        throw new Error(`Laravel artisan file not found: ${artisanPath}`)
    }

    phpProcess = spawn(phpBinary, [
        artisanPath,
        'serve',
        '--host=127.0.0.1',
        `--port=${APP_PORT}`,
        '--no-ansi'
    ], {
        cwd: PROJECT_ROOT,
        env: {
            ...process.env,
            APP_ENV: 'production',
            APP_DEBUG: 'false',
            DB_CONNECTION: 'sqlite',
            DB_DATABASE: databasePath
        },
        windowsHide: true,
        stdio: ['ignore', 'pipe', 'pipe']
    })

    phpProcess.stdout.on('data', (data) => {
        process.stdout.write(`[php] ${data}`)
    })

    phpProcess.stderr.on('data', (data) => {
        process.stderr.write(`[php] ${data}`)
    })

    phpProcess.on('error', (error) => {
        console.error('PHP process error:', error)
    })

    phpProcess.on('exit', (code, signal) => {
        if (!shuttingDown) {
            console.error(`PHP process exited unexpectedly (code: ${code}, signal: ${signal})`)

            if (mainWindow && !mainWindow.isDestroyed()) {
                loadHtml(createErrorPage('PHP stopped unexpectedly', `php artisan serve exited with code ${code ?? 'unknown'} and signal ${signal ?? 'none'}.`)).catch((error) => {
                    console.error('Could not show the PHP error page:', error)
                })
            }
        }

        phpProcess = null
    })

    return phpProcess
}

function stopPhpServer() {
    if (!phpProcess) {
        return
    }

    try {
        phpProcess.kill()
    } catch (error) {
        console.error('Failed to stop PHP server:', error)
    }

    phpProcess = null
}

function runArtisanCommand(args, databasePath) {
    return new Promise((resolve, reject) => {
        const phpBinary = getPhpBinary()
        const artisanPath = path.join(PROJECT_ROOT, 'artisan')
        const command = spawn(phpBinary, [artisanPath, ...args], {
            cwd: PROJECT_ROOT,
            env: {
                ...process.env,
                APP_ENV: 'production',
                APP_DEBUG: 'false',
                DB_CONNECTION: 'sqlite',
                DB_DATABASE: databasePath
            },
            windowsHide: true,
            stdio: ['ignore', 'pipe', 'pipe']
        })

        let stderr = ''

        command.stdout.on('data', (data) => {
            process.stdout.write(`[artisan] ${data}`)
        })

        command.stderr.on('data', (data) => {
            stderr += String(data)
            process.stderr.write(`[artisan] ${data}`)
        })

        command.on('error', reject)
        command.on('close', (code) => {
            if (code === 0) {
                resolve()
                return
            }

            reject(new Error(`artisan ${args.join(' ')} failed with exit code ${code}${stderr ? `: ${stderr.trim()}` : ''}`))
        })
    })
}

async function bootstrap() {
    if (process.platform === 'win32') {
        app.setAppUserModelId(APP_ID)
    }

    createWindow()
    await loadHtml(createLoadingPage('Preparing Laravel, PHP, and your database.'))

    const persistentDatabase = ensureDatabaseFile()
    ensureStorageLink()

    if (!(await isServerReady())) {
        spawnPhpServer(persistentDatabase)

        const ready = await waitForServerReady()
        if (!ready) {
            await loadHtml(createErrorPage('Laravel server did not start', `The app waited ${SERVER_READY_TIMEOUT_MS / 1000} seconds for ${APP_URL} and the server never became ready.`))
            return
        }
    }

    try {
        await runArtisanCommand(['migrate', '--force', '--no-interaction'], persistentDatabase)
    } catch (error) {
        console.error('Migration failed:', error)
        await loadHtml(createErrorPage('Laravel migration failed', error.message || String(error)))
        return
    }

    try {
        await mainWindow.loadURL(APP_URL)
    } catch (error) {
        console.error('Failed to load the Laravel app:', error)
        await loadHtml(createErrorPage('Unable to open the POS app', error.message || String(error)))
    }
}

const gotSingleInstanceLock = app.requestSingleInstanceLock()

if (!gotSingleInstanceLock) {
    app.quit()
} else {
    app.on('second-instance', () => {
        if (mainWindow) {
            if (mainWindow.isMinimized()) {
                mainWindow.restore()
            }

            mainWindow.show()
            mainWindow.focus()
        }
    })

    app.whenReady()
        .then(bootstrap)
        .catch((error) => {
            console.error('Bootstrap failed:', error)
            if (mainWindow && !mainWindow.isDestroyed()) {
                loadHtml(createErrorPage('Application startup failed', error.message || String(error))).catch((fallbackError) => {
                    console.error('Failed to display bootstrap error:', fallbackError)
                })
            }
        })
}

app.on('before-quit', () => {
    shuttingDown = true
    stopPhpServer()
})

app.on('window-all-closed', () => {
    if (process.platform !== 'darwin') {
        app.quit()
    }
})

app.on('activate', () => {
    if (BrowserWindow.getAllWindows().length === 0) {
        bootstrap().catch((error) => {
            console.error('Failed to relaunch the app window:', error)
        })
    }
})

process.on('uncaughtException', (error) => {
    console.error('Uncaught exception:', error)
})

process.on('unhandledRejection', (reason) => {
    console.error('Unhandled rejection:', reason)
})
