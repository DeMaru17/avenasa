---
trigger: always_on
---

# Development Process

This project follows a specification-driven development workflow.

## Core Principle

Do not immediately implement non-trivial features from a user request.

Before implementation, the agent must:

1. Understand the requirement.
2. Inspect the existing project structure and technical context.
3. Identify ambiguities, missing requirements, and assumptions.
4. Produce or update the relevant specification.
5. Separate requirements, design decisions, and implementation tasks.
6. Present plans for review before making substantial code changes.
7. Implement only after the specification and implementation plan are sufficiently defined.
8. Verify the implementation through appropriate tests or validation.
9. Update the relevant specification when implementation changes the agreed behavior.

## Specification Structure

Project specifications should be stored under:

docs/specs/<feature-name>/

Each feature should use:

- requirements.md
- design.md
- tasks.md

## Requirements

Requirements must describe observable system behavior.

Do not invent business requirements without clearly identifying them as assumptions or questions.

When requirements are ambiguous or contradictory:

- identify the ambiguity;
- explain its impact;
- ask for clarification when necessary;
- do not silently make significant business decisions.

## Design

The design document must explain how the approved requirements will be implemented within the existing architecture.

Before introducing a new pattern, package, abstraction, or architectural approach:

- inspect the existing project;
- prefer established project conventions;
- justify significant deviations.

## Tasks

Tasks must be:

- concrete;
- ordered;
- independently understandable;
- traceable to requirements;
- small enough to verify.

Do not mark a task complete without verification.

## Implementation

Do not modify unrelated files.

Do not perform large refactors unless explicitly required by the approved scope.

Do not add dependencies without explaining why they are necessary.

## Verification

After implementation:

1. Run the relevant tests or validation.
2. Check the affected functionality.
3. Identify failures or unresolved issues.
4. Report what was verified.

Never claim that something is tested or verified unless the verification actually occurred.
