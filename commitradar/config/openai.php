<?php
return [
    'commit_analysis_prompt' => <<<'PROMPT'
You are helping evaluate Git commits in a software repository.

For each commit listed below, return an object containing:
- `sha`: the commit SHA
- `commit_score`: a number from **1 to 10** that evaluates the **overall usefulness and quality** of the commit
- `commit_message_score`: a percentage from **0 to 100** that reflects the **clarity and quality of the commit message**

These two scores are **independent** and should be evaluated separately. A commit can have a high quality message but be useless (e.g., cosmetic change), or vice versa.

---

### Guidelines for `commit_score` (1–10):
Rate the **technical usefulness** and **impact** of the commit within the context of a project:
- Favor focused, non-trivial, atomic changes that improve functionality, structure, performance, or clarity.
- Penalize overly large commits that mix unrelated changes.
- Cosmetic-only changes (e.g., formatting, typos) should receive lower scores unless they affect critical files.
- Use the `files` list to **infer the type of project** (e.g., web frontend, API backend, data pipeline), and assess how relevant the changes are in that context.
- Take into account the size of the commit (`additions`, `deletions`) and whether the number of changed files is unusually large or small for the commit type.

---

### Guidelines for `commit_message_score` (0–100%):
- Good messages are:
  - Written in **imperative mood** ("Fix bug", not "Fixed bug")
  - **Clear**, **descriptive**, and to the point
  - Preferably include a **reference** to an issue/ticket
  - Avoid overly short ("Update", "Fix") or vague messages
  - One-liners are okay, but meaningless one-liners score low
- Formatting, grammar, and tone also affect score slightly.

---

### Input commits:
You will receive a JSON array of up to 5 commit objects, each with:
- `sha` (string)
- `message` (string)
- `files` (array of modified file paths)
- `additions` (number)
- `deletions` (number)

Return a JSON array of the same length, each object containing:

```json
{
  "sha": "abc123",
  "commit_score": 7,
  "commit_message_score": 85
}

Do not explain. Do not add extra text. Return only the JSON array as output.

Minta API call inputként (példa 5 commitra):

```json
[
  {
    "sha": "abc123",
    "message": "Refactor auth logic to support OAuth2",
    "files": ["src/auth/index.js", "src/utils/token.js"],
    "additions": 122,
    "deletions": 60
  },
  {
    "sha": "def456",
    "message": "fix",
    "files": ["README.md"],
    "additions": 1,
    "deletions": 0
  },
  ...
]
```

Minta válasz outputként:

```json
[
  {
    "sha": "abc123",
    "commit_score": 8,
    "commit_message_score": 91
  },
  {
    "sha": "def456",
    "commit_score": 2,
    "commit_message_score": 10
  },
  ...
]
```

PROMPT,
];
