#!/usr/bin/env node
const path = require('path');
const copy = require('copy-template-dir');
const vars = {
	prefix: ``,
	name: `test`,
	title: ``,
	ghusername: ``,
};

const inDir  = path.join( __dirname, 'templates/payment-gateway' );
const outDir = path.join( process.cwd(), vars.name );

copy(inDir, outDir, vars, (err, createdFiles) => {
	if (err) throw err
	createdFiles.forEach(filePath => console.log(`Created ${filePath}`))
	console.log('done!')
});
