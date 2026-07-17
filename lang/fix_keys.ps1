$file = 'ar.json'
$c = [System.IO.File]::ReadAllText($file, [System.Text.Encoding]::UTF8)

# Remove the 5 corrupted entries and the closing brace
$keysToRemove = @(
    'No supervised students found\.',
    'No chat requests yet\.',
    "You haven't sent any broadcasts yet\.",
    'Evaluate student performance and manage grade records for your courses\.',
    'Your guided internship students\.'
)

foreach ($key in $keysToRemove) {
    $c = $c -replace ",?\s*`"$key`":\s*`"[^`"]*`"", ''
}

# Trim trailing whitespace/newlines before closing brace
$c = $c.TrimEnd()
$c = $c.TrimEnd('}').TrimEnd()

# Append correct entries
$correct = @"
,
    "No supervised students found.": "`u{0644}`u{0627} `u{064A}`u{0648}`u{062C}`u{062F} `u{0637}`u{0644}`u{0627}`u{0628} `u{062A}`u{062D}`u{062A} `u{0625}`u{0634}`u{0631}`u{0627}`u{0641}`u{0643}.",
    "No chat requests yet.": "`u{0644}`u{0627} `u{062A}`u{0648}`u{062C}`u{062F} `u{0637}`u{0644}`u{0628}`u{0627}`u{062A} `u{062F}`u{0631}`u{062F}`u{0634}`u{0629} `u{0628}`u{0639}`u{062F}.",
    "You haven't sent any broadcasts yet.": "`u{0644}`u{0645} `u{062A}`u{0631}`u{0633}`u{0644} `u{0623}`u{064A} `u{0631}`u{0633}`u{0627}`u{0626}`u{0644} `u{0628}`u{062B} `u{0628}`u{0639}`u{062F}.",
    "Evaluate student performance and manage grade records for your courses.": "`u{062A}`u{0642}`u{064A}`u{064A}`u{0645} `u{0623}`u{062F}`u{0627}`u{0621} `u{0627}`u{0644}`u{0637}`u{0644}`u{0627}`u{0628} `u{0648}`u{0625}`u{062F}`u{0627}`u{0631}`u{0629} `u{0633}`u{062C}`u{0644}`u{0627}`u{062A} `u{0627}`u{0644}`u{062F}`u{0631}`u{062C}`u{0627}`u{062A} `u{0644}`u{0645}`u{0648}`u{0627}`u{062F}`u{0643} `u{0627}`u{0644}`u{062F}`u{0631}`u{0627}`u{0633}`u{064A}`u{0629}.",
    "Your guided internship students.": "`u{0637}`u{0644}`u{0627}`u{0628} `u{0627}`u{0644}`u{062A}`u{062F}`u{0631}`u{064A}`u{0628} `u{0627}`u{0644}`u{0645}`u{064A}`u{062F}`u{0627}`u{0646}`u{064A} `u{0627}`u{0644}`u{0645}`u{0634}`u{0631}`u{0641} `u{0639}`u{0644}`u{064A}`u{0647}`u{0645}."
}
"@

$c = $c + $correct + "`n}"

[System.IO.File]::WriteAllText($file, $c, (New-Object System.Text.UTF8Encoding $false))
Write-Host "Done"
