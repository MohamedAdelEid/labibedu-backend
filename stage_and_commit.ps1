# Stage all changes
Write-Host "Staging all changes..." -ForegroundColor Cyan
git add -A

# Show status
Write-Host "`nGit Status:" -ForegroundColor Yellow
git status --short

# Read commit message from file
$commitMessage = Get-Content -Path "commit_message.txt" -Raw

# Commit with message
Write-Host "`nCommitting changes..." -ForegroundColor Cyan
git commit -m $commitMessage

# Show last commit
Write-Host "`nCommit completed! Last commit:" -ForegroundColor Green
git log -1 --oneline

