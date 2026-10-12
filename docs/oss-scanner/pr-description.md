## Enrol `projects/nukeviet`

I have verified each of the following before submitting this project:

- [ ] I am a core maintainer of this project.
- [ ] I accept the [OSS Scanner terms](https://red.anthropic.com/oss-scanner/terms/).
- [ ] I confirm that I believe my project meets the inclusion criteria and is an established project that users depend on.
- [ ] `project.yaml` follows the README (one project per PR; `tools/validate.py` passes locally).
- [ ] `tools/check nukeviet` builds the project, and its tests run in the shell that follows.
- [ ] `primary_contact` must be the email address of the primary security contact.
- [ ] There is a `Dockerfile` that builds the project (required) in one of two places: in the project's repository at the path `dockerfile:` names, or next to `project.yaml` in this repo.
- [ ] `threat_model.md` is written for this project (recommended but optional), placed in the same way: in the repository at the path `threat_model:` names, or next to `project.yaml` in this repo.

To change an enrolment, edit `project.yaml`; to pause reports, set `disabled: true`; to withdraw the project, remove `projects/<name>/`.

Context (optional):

NukeViet (https://github.com/nukeviet/nukeviet) is the first open-source CMS from Vietnam, developed since 2009 and led by VINADES.,JSC. It is a PHP/MySQL web application that handles untrusted input from the internet, and it is used for news portals, business sites and, through the NukeViet eGovernment edition, by Vietnamese government agencies and local authorities.

I am enrolling the project on behalf of VINADES.,JSC, with the company's permission. I am a public admin of the `nukeviet` GitHub organization and an admin of `nukeviet/nukeviet`, one of its top contributors (https://github.com/nukeviet/nukeviet/graphs/contributors, https://github.com/nukeviet/nukeviet/commits/nukeviet5.0?author=hoaquynhtim99), and the primary security contact. The project already runs a coordinated disclosure process through GitHub private vulnerability reporting and the WhiteHub bug bounty program, and has published 23 security advisories (https://github.com/nukeviet/nukeviet/security/advisories).

Branch: `nukeviet5.0`, the development line for the next major release (NukeViet 5). The Dockerfile and threat model live in the repository under `.oss-scanner/`.
