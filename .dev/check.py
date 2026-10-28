#!/usr/bin/env python3
"""Run repository checks without installing a production build toolchain."""
import json
from pathlib import Path
import subprocess
import sys

ROOT = Path(__file__).resolve().parent.parent
php_files = sorted(p for p in ROOT.rglob('*.php') if not {'.git', 'node_modules'}.intersection(p.parts))
for path in php_files:
    result = subprocess.run(['php', '-l', str(path)], capture_output=True, text=True)
    if result.returncode:
        sys.exit(result.stdout + result.stderr)
print(f'PHP syntax checks passed: {len(php_files)}', flush=True)
json_files = [ROOT / 'theme.json', *sorted((ROOT / 'styles').rglob('*.json')), *sorted((ROOT / 'plugin').rglob('*.json'))]
for path in json_files:
    json.loads(path.read_text())
print(f'JSON validation passed: {len(json_files)}', flush=True)
for command in [
    ['php', '.dev/tests/content.php'],
    *[[sys.executable, f'.dev/{audit}.py'] for audit in ['heading_audit', 'link_audit', 'rhythm_audit', 'gradient_contrast']],
]:
    subprocess.run(command, cwd=ROOT, check=True)
