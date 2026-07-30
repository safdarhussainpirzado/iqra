# Software Requirements Specification (SRS)
## Project Name: IQRA Educational Platform
**Document Version:** 1.0.0  
**Date:** July 30, 2026  
**Status:** Approved Specification  

---

## 1. Introduction

### 1.1 Purpose
This Software Requirements Specification (SRS) document provides a detailed specification for the development and deployment of **IQRA**, an enterprise-grade, SaaS-quality Educational Content Management and Question Paper Generation System. It serves as the definitive reference for software architects, backend/frontend engineers, QA automation suites, and system administrators.

### 1.2 Scope
IQRA is designed as a high-performance, single-tenant/multi-role monolith Single Page Application (SPA) built on **Laravel** and **Alpine.js**. The platform facilitates content ingestion, optical character recognition (OCR) of multi-lingual documents (English, Urdu, Sindhi), centralized question bank management, dynamic question paper synthesis, and web content import.

**Out of Scope:**
- AI/LLM integrations, vector embeddings, chatbots, or non-deterministic recommendation engines. All operations within IQRA are deterministic and rule-based.

### 1.3 Definitions & Acronyms
- **SPA:** Single Page Application
- **RBAC:** Role-Based Access Control
- **OCR:** Optical Character Recognition
- **MCQ:** Multiple Choice Question
- **LTS:** Long-Term Support
- **MIME:** Multipurpose Internet Mail Extensions
- **JWT / Sanctum:** Laravel Sanctum token-based authentication mechanism

---

## 2. Overall Description

### 2.1 Product Perspective
IQRA is a web-based educational management monolith that operates with a decoupled frontend UI bundled via Vite and driven by Alpine.js. The application integrates with a local database engine (MySQL 8.0+), a key-value caching and job queue broker (Redis), and system-level document processing utilities (Tesseract OCR, Poppler/PDF toolkits).

```
+-----------------------------------------------------------------------+
|                            IQRA SPA UI                                |
|                 (Alpine.js + Tailwind CSS + Vite)                     |
+-----------------------------------------------------------------------+
                                   |
                             REST / JSON API
                                   |
+-----------------------------------------------------------------------+
|                          Laravel Application                          |
|  +-------------------+  +--------------------+  +------------------+  |
|  | Auth & Policy Middleware | Paper Generator Engine | OCR Worker Pool  |  |
|  +-------------------+  +--------------------+  +------------------+  |
+-----------------------------------------------------------------------+
             |                              |                       |
     +---------------+              +---------------+       +---------------+
     | MySQL Database|              | Redis Queue   |       | Local Storage |
     +---------------+              +---------------+       +---------------+
```

### 2.2 Product Functions
1. **Academic Hierarchy Management:** Configurable Boards, Classes (Class 1 to 12), Subjects, Chapters, Topics, and Pages.
2. **Document & Ingestion Engine:** Upload, parse, extract, and index multi-format documents (PDF, DOCX, TXT, CSV, Excel, JSON, HTML).
3. **Multi-lingual OCR:** Queue background OCR jobs using Tesseract OCR for scanned English, Urdu, and Sindhi documents.
4. **Question Bank Management:** Centralized repository for MCQs, Short Questions, Long Questions, True/False, and Matching items with tag metadata.
5. **Dynamic Paper Generation:** Rule-based synthesis of printable Question Papers and corresponding Answer Keys.
6. **Website Content Importer:** Asynchronous crawler for downloading and converting external web resources and documents.
7. **Analytics & Activity Audit:** Comprehensive logging of user sessions, resource mutations, and paper generation metrics.

### 2.3 User Classes and Roles

| Role | Access Level & Core Responsibilities |
| :--- | :--- |
| **Super Admin** | Full access. System configuration, board administration, role assignment, system health monitoring. |
| **Admin** | Managing academic hierarchies, approving uploaded materials, managing user accounts. |
| **Teacher** | Accessing notes, generating custom Question Papers, exporting PDFs/DOCX, downloading answer keys. |
| **Paper Setter** | Creating and validating questions, parsing past papers, managing the Question Bank repository. |
| **Student** | Read-only viewer for learning materials, notes, and public practice banks. |

### 2.4 Operating Environment
- **Operating System:** Linux (Ubuntu 22.04 LTS / Alpine Docker containers)
- **Runtime Environment:** PHP 8.4+ with `pcntl`, `gd`, `intl`, `pdo_mysql`, `bcmath` extensions.
- **Web Server:** Nginx 1.24+ reverse proxy to PHP-FPM.
- **Database Engine:** MySQL 8.0+ with InnoDB and Full-Text search capability.
- **Cache / Message Broker:** Redis 7.0+.
- **OCR Engine:** Tesseract OCR (v5.x) with `eng`, `urd`, `sin` language data packs.

### 2.5 Design & Implementation Constraints
1. **Zero External CDNs:** All frontend dependencies (JS libraries, CSS stylesheets, web fonts, vector icons) MUST be compiled locally via Vite into the `/public` build directory.
2. **Local Font & Styling Bundling:** Web fonts (e.g., Instrument Sans, Naskh scripts for Urdu/Sindhi) must be stored locally in `resources/fonts`.
3. **PHP Version Compatibility:** Application code must target PHP 8.4 features while maintaining clean execution on local PHP 8.3 CLI environments.

---

## 3. System Features & Functional Requirements

### 3.1 Authentication & Authorization Module

#### 3.1.1 Description
Provides secure token and session-based authentication using Laravel Sanctum, accompanied by granular Policy-based access control.

#### 3.1.2 Functional Requirements
- **FR-AUTH-01:** Users shall authenticate using valid email/username and password credentials.
- **FR-AUTH-02:** Password policies shall enforce a minimum of 8 characters with mixed-case, numeric, and symbol constraints.
- **FR-AUTH-03:** Rate limiting shall throttle authentication attempts to a maximum of 5 failed attempts per minute per IP address.
- **FR-AUTH-04:** Every system endpoint shall verify user permissions using Laravel Policies matching assigned RBAC roles.

---

### 3.2 Academic Hierarchy Module

#### 3.2.1 Description
Manages organizational structure for categorizing educational content.

#### 3.2.2 Hierarchy Structure
$$\text{Board} \longrightarrow \text{Class} \longrightarrow \text{Subject} \longrightarrow \text{Chapter} \longrightarrow \text{Topic} \longrightarrow \text{Page}$$

#### 3.2.3 Functional Requirements
- **FR-ACAD-01:** Super Admins shall create, update, soft-delete, and restore Boards (e.g., FBISE, Punjab Board, Sindh Board).
- **FR-ACAD-02:** System shall support Class levels from Class 1 through Class 12.
- **FR-ACAD-03:** Subject entities shall support localized language flags (`English`, `Urdu`, `Sindhi`).
- **FR-ACAD-04:** Chapters and Topics shall retain parent foreign keys with index optimizations for rapid lookup.

---

### 3.3 Material Ingestion & OCR Processing Pipeline

#### 3.3.1 Description
Handles ingestion of documents and background OCR processing.

```
[Uploaded File] ──► [Storage Validation] ──► [Database Record]
                                                   │
                                                   ▼
                                         [Enqueue Redis Job]
                                                   │
                                                   ▼
                                        [Tesseract OCR Engine]
                                                   │
                                                   ▼
                                         [Save Extracted Text]
```

#### 3.3.2 Functional Requirements
- **FR-ING-01:** System shall validate file MIME types for `.pdf`, `.doc`, `.docx`, `.txt`, `.csv`, `.xlsx`, `.json`, `.html`.
- **FR-ING-02:** Document upload shall extract file metadata (file size, page count, extension) and store original files securely in local disk storage.
- **FR-ING-03:** Scanned PDFs or images shall trigger an asynchronous Redis queue job executing Tesseract OCR.
- **FR-ING-04:** OCR engine shall support language parameters for English (`eng`), Urdu (`urd`), and Sindhi (`sin`).
- **FR-ING-05:** Extracted text content shall be saved into the database and indexed using MySQL Full-Text Search.

---

### 3.4 Question Bank & MCQ Repository

#### 3.4.1 Description
Manages atomic questions for test composition.

#### 3.4.2 Functional Requirements
- **FR-QB-01:** System shall support Question Types: Multiple Choice (MCQ), Short Answer, Long Answer, Fill-in-the-Blanks, True/False, and Column Matching.
- **FR-QB-02:** MCQs shall store multiple option records (`mcq_options`) with strict `is_correct` boolean indicators and optional explanations.
- **FR-QB-03:** Each question record shall store metadata tags: Board, Class, Subject, Chapter, Marks, Difficulty Level (Easy, Medium, Hard), and Language.
- **FR-QB-04:** File imports (Excel, CSV, JSON) shall auto-parse batch questions and perform hash checking to prevent duplicate insertions.

---

### 3.5 Dynamic Question Paper Generator

#### 3.5.1 Description
Synthesizes customized examination papers based on user criteria.

#### 3.5.2 Selection Logic
$$\text{Total Marks} = \sum_{i=1}^{n} (\text{Question}_i \times \text{Marks}_i)$$

#### 3.5.3 Functional Requirements
- **FR-GEN-01:** Teachers shall define paper criteria: Subject, Chapter list, Total Marks, Target Difficulty ratio, and Question Type breakdown.
- **FR-GEN-02:** The generator engine shall randomly pull non-duplicate questions matching criteria parameters.
- **FR-GEN-03:** The output shall generate two distinct documents:
  1. Student Question Paper (formatted with institution header, instructions, section headers).
  2. Teacher Answer Key (including correct options and solution guidelines).
- **FR-GEN-04:** Papers shall be exportable to printable PDF format.

---

### 3.6 Glassmorphism SPA User Interface

#### 3.6.1 Description
Delivers a high-performance single page UI with modern SaaS design semantics.

#### 3.6.2 Functional Requirements
- **FR-UI-01:** Interface shall utilize Tailwind CSS Glassmorphism styling (`backdrop-blur-md`, subtle border highlights, custom dark/light color palettes).
- **FR-UI-02:** Mathematical formulas shall render client-side using locally bundled KaTeX libraries.
- **FR-UI-03:** PDF documents shall be previewed in-browser using locally bundled PDF.js.
- **FR-UI-04:** Interface shall provide dynamic Dark Mode and Light Mode toggles.

---

## 4. Non-Functional Requirements

### 4.1 Performance Requirements
- **NFR-PERF-01:** API response times for read queries shall be $< 150\text{ ms}$ for 95% of requests.
- **NFR-PERF-02:** Document text extraction and OCR jobs shall process in background workers without blocking HTTP request threads.
- **NFR-PERF-03:** Vite bundle size for frontend assets shall be optimized under 500 KB gzip compressed.

### 4.2 Security Requirements
- **NFR-SEC-01 (Zero-CDN Security):** Zero external script execution. All JavaScript and CSS code must originate from the application domain.
- **NFR-SEC-02:** Protection against OWASP Top 10 vulnerabilities (CSRF token verification, XSS output encoding, PDO prepared statements for SQL injection prevention).
- **NFR-SEC-03:** Uploaded files must undergo strict MIME type inspection and stored with un-executable file permissions.

### 4.3 Reliability & Availability
- **NFR-REL-01:** System shall utilize Redis queue retry mechanisms with exponential backoff for failed document parsing jobs.
- **NFR-REL-02:** Soft deletes shall be implemented across all core data models to prevent accidental data loss.

---

## 5. System Architecture & Database Schema

### 5.1 Entity Relationship Diagram Structure

```
+----------------+       +-----------------+       +-----------------+
|     boards     |       |     classes     |       |    subjects     |
+----------------+       +-----------------+       +-----------------+
| id (PK)        |       | id (PK)         |       | id (PK)         |
| name           | 1   * | name            | 1   * | name            |
| code           |-------| code            |-------| code            |
+----------------+       +-----------------+       +-----------------+
                                                           | 1
                                                           |
                                                           | *
                         +-----------------+       +-----------------+
                         |    chapters     |       |  question_bank  |
                         +-----------------+       +-----------------+
                         | id (PK)         | 1   * | chapter_id (FK) |
                         | subject_id (FK) |-------| question_text   |
                         | title           |       | type, marks     |
                         +-----------------+       +-----------------+
                                                           | 1
                                                           | *
                                                   +-----------------+
                                                   |   mcq_options   |
                                                   +-----------------+
                                                   | id (PK)         |
                                                   | question_id(FK) |
                                                   | option_text     |
                                                   | is_correct      |
                                                   +-----------------+
```

---

## 6. Verification & Compliance Matrix

| Requirement ID | Verification Method | Automated Test Component |
| :--- | :--- | :--- |
| **FR-AUTH-01** | Integration Test | `Feature/Auth/LoginTest.php` |
| **FR-ACAD-01** | Unit / Integration Test | `Feature/Academic/BoardManagementTest.php` |
| **FR-ING-03** | Queue Worker Test | `Unit/Jobs/ProcessOcrJobTest.php` |
| **FR-QB-04** | Import Test | `Feature/QuestionBank/ImportMcqTest.php` |
| **FR-GEN-01** | Algorithm Unit Test | `Unit/Services/PaperGeneratorServiceTest.php` |
| **NFR-SEC-01** | Static Asset Audit | `npm run build` Bundle Inspector |

---
**End of Software Requirements Specification (SRS)**
