# Graph Report - d:\Kuliah\Semester 6\Compro\GhinaTourTravel  (2026-05-05)

## Corpus Check
- 177 files · ~36,370 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 279 nodes · 304 edges · 75 communities (66 shown, 9 thin omitted)
- Extraction: 94% EXTRACTED · 6% INFERRED · 0% AMBIGUOUS · INFERRED: 17 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- [[_COMMUNITY_Frontend Assets|Frontend Assets]]
- [[_COMMUNITY_Packages & Rundowns|Packages & Rundowns]]
- [[_COMMUNITY_Frontend Assets|Frontend Assets]]
- [[_COMMUNITY_Chatbot System|Chatbot System]]
- [[_COMMUNITY_Order Management|Order Management]]
- [[_COMMUNITY_Authentication & Users|Authentication & Users]]
- [[_COMMUNITY_Packages & Rundowns|Packages & Rundowns]]
- [[_COMMUNITY_Chatbot System|Chatbot System]]
- [[_COMMUNITY_Packages & Rundowns|Packages & Rundowns]]
- [[_COMMUNITY_Packages & Rundowns|Packages & Rundowns]]
- [[_COMMUNITY_Packages & Rundowns|Packages & Rundowns]]
- [[_COMMUNITY_Service Providers|Service Providers]]
- [[_COMMUNITY_Authentication & Users|Authentication & Users]]
- [[_COMMUNITY_UI Components|UI Components]]
- [[_COMMUNITY_Packages & Rundowns|Packages & Rundowns]]
- [[_COMMUNITY_Miscellaneous|Miscellaneous]]
- [[_COMMUNITY_Miscellaneous|Miscellaneous]]

## God Nodes (most connected - your core abstractions)
1. `forEach()` - 12 edges
2. `PaketController` - 10 edges
3. `w()` - 10 edges
4. `Gallery` - 9 edges
5. `AuthController` - 8 edges
6. `GalleryController` - 8 edges
7. `PesananController` - 8 edges
8. `f()` - 8 edges
9. `g()` - 8 edges
10. `ChatbotController` - 7 edges

## Surprising Connections (you probably didn't know these)
- `n()` --calls--> `V()`  [EXTRACTED]
  public/build/assets/app-DU0szZG0.js → public/build/assets/app-DU0szZG0.js  _Bridges community 2 → community 0_

## Communities (75 total, 9 thin omitted)

### Community 0 - "Frontend Assets"
Cohesion: 0.06
Nodes (38): accessor(), cancel(), clear(), concat(), ct(), delete(), dt(), forEach() (+30 more)

### Community 1 - "Packages & Rundowns"
Cohesion: 0.1
Nodes (3): GalleryController, PageController, Gallery

### Community 2 - "Frontend Assets"
Cohesion: 0.15
Nodes (20): A(), constructor(), _e(), et(), f(), g(), getUri(), he() (+12 more)

### Community 3 - "Chatbot System"
Cohesion: 0.16
Nodes (4): CompanyProfileController, ChatbotController, CompanyProfile, CompanyProfileSeeder

### Community 4 - "Order Management"
Cohesion: 0.12
Nodes (4): DashboardController, PesananController, Pesanan, PesananSeeder

### Community 5 - "Authentication & Users"
Cohesion: 0.13
Nodes (3): AuthController, User, DatabaseSeeder

### Community 7 - "Chatbot System"
Cohesion: 0.73
Nodes (5): appendMessage(), fetchInitialMenu(), hideTyping(), sendMessage(), showTyping()

### Community 13 - "UI Components"
Cohesion: 0.5
Nodes (3): components.layout.footer, components.layout.navbar, components.layout.scripts

## Knowledge Gaps
- **5 isolated node(s):** `Controller`, `components.layout.navbar`, `components.layout.footer`, `components.layout.scripts`, `TestCase`
  These have ≤1 connection - possible missing edges or undocumented components.
- **9 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Pesanan` connect `Community 4` to `Community 3`?**
  _High betweenness centrality (0.010) - this node is a cross-community bridge._
- **Are the 4 inferred relationships involving `Gallery` (e.g. with `.index()` and `.store()`) actually correct?**
  _`Gallery` has 4 INFERRED edges - model-reasoned connections that need verification._
- **What connects `Controller`, `components.layout.navbar`, `components.layout.footer` to the rest of the system?**
  _5 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Community 0` be split into smaller, more focused modules?**
  _Cohesion score 0.06 - nodes in this community are weakly interconnected._
- **Should `Community 1` be split into smaller, more focused modules?**
  _Cohesion score 0.1 - nodes in this community are weakly interconnected._
- **Should `Community 4` be split into smaller, more focused modules?**
  _Cohesion score 0.12 - nodes in this community are weakly interconnected._
- **Should `Community 5` be split into smaller, more focused modules?**
  _Cohesion score 0.13 - nodes in this community are weakly interconnected._