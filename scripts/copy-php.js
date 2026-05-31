const fs = require('fs')
const path = require('path')

exports.default = async function copyPhp(context) {
    const sourceRoot = process.env.PHP_HOME || 'E:\\php'
    const destinationRoot = path.join(context.appOutDir, 'resources', 'php')

    if (!fs.existsSync(sourceRoot)) {
        throw new Error(`PHP folder not found: ${sourceRoot}`)
    }

    fs.rmSync(destinationRoot, { recursive: true, force: true })
    fs.mkdirSync(path.dirname(destinationRoot), { recursive: true })
    fs.cpSync(sourceRoot, destinationRoot, { recursive: true })
}
