<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Dashboard - LifeLine</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
  .rotate-180 {
    transform: rotate(180deg);
  }
  
  .transition-transform {
    transition: transform 0.3s ease;
  }
  
  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'DM Sans',sans-serif;background:#09090B;color:#fafafa;min-height:100vh}
h1,h2,h3,h4,h5,h6{font-family:'Space Grotesk',sans-serif}
.bg-grid{background-image:radial-gradient(ellipse 80% 50% at 50% 0%,rgba(239,68,68,.06) 0%,transparent 60%),radial-gradient(ellipse 60% 40% at 80% 100%,rgba(45,212,191,.04) 0%,transparent 60%),linear-gradient(rgba(63,63,70,.15) 1px,transparent 1px),linear-gradient(90deg,rgba(63,63,70,.15) 1px,transparent 1px);background-size:100% 100%,100% 100%,40px 40px,40px 40px}
.card{background:#18181B;border:1px solid #27272A;border-radius:12px;transition:all .25s}
.card:hover{border-color:#3F3F46;transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,.3)}
.card-s{background:#18181B;border:1px solid #27272A;border-radius:12px}
.quick-card{cursor:pointer;position:relative;overflow:hidden}
.quick-card::after{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(239,68,68,.08),transparent);opacity:0;transition:opacity .3s}
.quick-card:hover::after{opacity:1}
.sidebar{width:220px}.nav-item{transition:all .2s;position:relative;cursor:pointer}
.nav-item::before{content:'';position:absolute;left:0;top:50%;transform:translateY(-50%);width:3px;height:0;background:#EF4444;border-radius:0 4px 4px 0;transition:height .2s}
.nav-item.active::before{height:60%}.nav-item.active{background:rgba(239,68,68,.1);color:#EF4444}
.sos-fab{position:fixed;bottom:24px;right:24px;z-index:5000;width:56px;height:56px;border-radius:50%;background:#EF4444;color:white;border:none;font-size:18px;cursor:pointer;box-shadow:0 4px 20px rgba(239,68,68,.4);transition:all .3s}
.sos-fab:hover{transform:scale(1.1);box-shadow:0 6px 30px rgba(239,68,68,.6)}
.page{display:none;animation:fadeSlide .3s ease}.page.active{display:block}#pg-contacts.active{display:flex}
@keyframes fadeSlide{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.modal-bg{position:fixed;inset:0;z-index:7000;background:rgba(0,0,0,.7);backdrop-filter:blur(8px);display:none;justify-content:center;align-items:center;padding:20px}
.modal-bg.open{display:flex}
.modal-box{background:#18181B;border:1px solid #2722A;border-radius:16px;max-width:560px;width:100%;max-height:80vh;overflow-y:auto;animation:modalIn .3s ease}
@keyframes modalIn{from{opacity:0;transform:scale(.95) translateY(10px)}to{opacity:1;transform:scale(1) translateY(0)}}
.sos-overlay{position:fixed;inset:0;z-index:9999;background:rgba(127,29,29,.95);backdrop-filter:blur(20px);display:none}
.sos-overlay.open{display:flex}
.sos-pulse-border{animation:sosBorder 1.5s ease-in-out infinite}
@keyframes sosBorder{0%,100%{box-shadow:inset 0 0 40px rgba(239,68,68,.3)}50%{box-shadow:inset 0 0 80px rgba(239,68,68,.6)}}
.toast{position:fixed;bottom:24px;right:24px;z-index:8000;padding:12px 20px;border-radius:10px;background:#27272A;border:1px solid #3F3F46;color:#FAFAFA;font-size:14px;animation:toastIn .3s ease,toastOut .3s ease 2.7s forwards;max-width:340px}
@keyframes toastIn{from{opacity:0;transform:translateX(40px)}to{opacity:1;transform:translateX(0)}}
@keyframes toastOut{from{opacity:1}to{opacity:0;transform:translateX(40px)}}
.sev-critical{background:rgba(239,68,68,.15);color:#FCA5A5;border:1px solid rgba(239,68,68,.3)}
.sev-urgent{background:rgba(249,115,22,.15);color:#FDBA74;border:1px solid rgba(249,115,22,.3)}
.sev-moderate{background:rgba(234,179,8,.15);color:#FDE047;border:1px solid rgba(234,179,8,.3)}
.sev-minor{background:rgba(34,197,94,.15);color:#86EFAC;border:1px solid rgba(34,197,94,.3)}
.step-dot{width:10px;height:10px;border-radius:50%;background:#3F3F46;transition:all .3s}
.step-dot.active{background:#EF4444;box-shadow:0 0 8px rgba(239,68,68,.5)}
.step-dot.done{background:#2DD4BF}
.search-input{background:#18181B;border:1px solid #272PA;border-radius:10px;padding:10px 16px 10px 42px;width:100%;outline:none;transition:border-color .2s}
.search-input:focus{border-color:#EF4444}.search-input::placeholder{color:#71717A}
.kit-check{appearance:none;width:20px;height:20px;border:2px solid #3F3F46;border-radius:6px;cursor:pointer;transition:all .2s;flex-shrink:0}
.kit-check:checked{background:#2DD4BF;border-color:#2DD4BF;background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 16 16' fill='%2309090B' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3E%3C/svg%3E")}
.kit-check:checked+span{text-decoration:line-through;color:#71717A}
.cpr-pulse{animation:cprPulse calc(60s/var(--bpm)) ease-in-out infinite}
@keyframes cprPulse{0%,100%{transform:scale(.85);opacity:.5}50%{transform:scale(1.1);opacity:1}}
@keyframes pulseSlow{0%,100%{transform:scale(1)}50%{transform:scale(1.05)}}
@keyframes pulseRing{0%,100%{box-shadow:0 0 0 0 rgba(239,68,68,.4)}70%{box-shadow:0 0 0 15px rgba(239,68,68,0)}}
.float-card{animation:floatCard 6s ease-in-out infinite}
@keyframes floatCard{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
.body-zone{fill:rgba(45,212,191,.08);stroke:rgba(45,212,191,.25);stroke-width:1;cursor:pointer;transition:all .3s}
.body-zone:hover{fill:rgba(239,68,68,.2);stroke:#EF4444;stroke-width:1.5}
.body-outline{fill:none;stroke:#3F3F46;stroke-width:1.5}
@media(max-width:768px){.sidebar{display:none!important}.mob-bar{display:flex!important}.main-c{padding-bottom:80px!important}}
</style>
</head>
<body class="bg-grid">

<!-- Inline script to ensure goTo function is available immediately -->
<script>
function goTo(pg) {
    console.log('goTo called with:', pg);
    try {
        document.querySelectorAll('.page').forEach(function(e) {
            e.classList.remove('active')
        });
        document.getElementById('pg-' + pg).classList.add('active');
        document.querySelectorAll('#sideNav .nav-item').forEach(function(e) {
            e.classList.remove('active');
            e.classList.add('text-zinc-400')
        });
        var i = {
            dashboard: 1,
            bodymap: 2,
            checker: 3,
            guide: 4,
            cpr: 5,
            contacts: 6
        };
        var b = document.querySelector('#sideNav .nav-item:nth-child(' + i[pg] + ')');
        if (b) {
            b.classList.add('active');
            b.classList.remove('text-zinc-400')
        }
        console.log('Navigation successful to:', pg);
    } catch (error) {
        console.error('Error in goTo function:', error);
    }
}

// Store checker initial state
var checkerQ1;

// Make goTo function globally available
window.goTo = goTo;

// Button-based search function
function performSearchClick() {
    console.log('Search button clicked!');
    const searchInput = document.getElementById('emergencySearch');
    const searchResults = document.getElementById('searchResults');
    
    if (!searchInput || !searchResults) {
        console.error('Search elements not found!');
        alert('Search elements not found!');
        return;
    }
    
    const query = searchInput.value.trim();
    console.log('Search query:', query);
    
    if (query.length < 2) {
        searchResults.innerHTML = `
            <div class="p-4 text-center text-zinc-400">
                <i class="fa-solid fa-search mb-2 text-2xl"></i>
                <p>Please enter at least 2 characters to search.</p>
            </div>
        `;
        searchResults.classList.remove('hidden');
        return;
    }
    
    // Call the existing performSearch function
    window.performSearch(query);
}

// Global performSearch function
window.performSearch = function(query) {
    const searchResults = document.getElementById('searchResults');
    if (!searchResults) return;
    
    console.log('Searching for:', query);
    
    // Show loading state
    searchResults.innerHTML = `
        <div class="p-4 text-center text-zinc-400">
            <div class="inline-block animate-spin w-6 h-6 border-2 border-zinc-600 border-t-red-400 rounded-full mb-2"></div>
            <p>Getting AI recommendations...</p>
        </div>
    `;
    searchResults.classList.remove('hidden');
    
    // Get AI recommendations
    console.log('performSearch called with query:', query);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    console.log('CSRF Token found:', csrfToken ? 'Yes' : 'No');
    
    fetch('/emergency-ai/recommendations', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ query: query })
    })
    .then(response => {
        console.log('Response received, status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success && data.data) {
            displayAIRecommendations(data.data, query);
        } else {
            console.log('AI failed, using fallback search');
            // Fallback to local search if AI fails
            performFallbackSearch(query);
        }
    })
    .catch(error => {
        console.error('AI search error:', error);
        console.log('Error caught, using fallback search');
        // Fallback to local search
        performFallbackSearch(query);
    });
};

// Direct search setup that works immediately
function setupEmergencySearch() {
    console.log('Setting up emergency search...');
    const searchInput = document.getElementById('emergencySearch');
    const searchResults = document.getElementById('searchResults');
    
    if (!searchInput || !searchResults) {
        console.error('Search elements not found!');
        return false;
    }
    
    console.log('Search elements found, setting up event listeners...');
    
    // Add Enter key support
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            performSearchClick();
        }
    });
    
    return true;
}

// Test function to bypass event listener issues
function testSearch() {
    console.log('Test search button clicked!');
    const searchInput = document.getElementById('emergencySearch');
    const searchResults = document.getElementById('searchResults');
    
    if (!searchInput || !searchResults) {
        console.error('Search elements not found!');
        alert('Search elements not found!');
        return;
    }
    
    const query = searchInput.value.trim() || 'difficulty breathing';
    console.log('Testing with query:', query);
    
    // Show loading state
    searchResults.innerHTML = `
        <div class="p-4 text-center text-zinc-400">
            <div class="inline-block animate-spin w-6 h-6 border-2 border-zinc-600 border-t-red-400 rounded-full mb-2"></div>
            <p>Getting AI recommendations...</p>
        </div>
    `;
    searchResults.classList.remove('hidden');
    
    // Direct API call
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/emergency-ai/recommendations', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ query: query })
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success && data.data) {
            displayAIRecommendations(data.data, query);
        } else {
            performFallbackSearch(query);
        }
    })
    .catch(error => {
        console.error('API error:', error);
        performFallbackSearch(query);
    });
}

// Additional safeguard - ensure goTo is always available
if (typeof window.goTo === 'undefined') {
    window.goTo = function(pg) {
        console.error('goTo function called but not properly initialized');
    };
}

console.log('goTo function loaded and available globally:', typeof goTo);

// Make display functions globally available
window.displayAIRecommendations = function(aiData, query) {
    const searchResults = document.getElementById('searchResults');
    if (!searchResults) return;
    
    const recommendations = aiData.recommendations || [];
    
    if (recommendations.length === 0) {
        searchResults.innerHTML = `
            <div class="p-4 text-center text-zinc-400">
                <i class="fa-solid fa-search mb-2 text-2xl"></i>
                <p>No AI recommendations found for "${query}".</p>
            </div>
        `;
        return;
    }
    
    let html = '';
    
    // Add AI badge if powered by AI
    if (aiData.aiPowered) {
        html += `
            <div class="p-3 bg-green-600/10 border-b border-zinc-800">
                <div class="flex items-center gap-2 text-green-400 text-sm">
                    <i class="fa-solid fa-brain"></i>
                    <span>AI-Powered Recommendations</span>
                </div>
            </div>
        `;
    }
    
    // Display each recommendation
    recommendations.forEach(rec => {
        const severityColor = rec.severity === 'critical' ? 'red' : 
                             rec.severity === 'urgent' ? 'orange' : 
                             rec.severity === 'moderate' ? 'yellow' : 'green';
        
        html += `
            <div class="p-4 border-b border-zinc-800 hover:bg-zinc-800/50 cursor-pointer transition-colors" onclick="showAIRecommendationDetails(this)" data-rec='${JSON.stringify(rec).replace(/'/g, "&apos;")}' data-ai='${JSON.stringify(aiData).replace(/'/g, "&apos;")}'>
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-heart-pulse text-${severityColor}-400"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <h4 class="font-semibold text-white">${rec.condition}</h4>
                            <span class="sev-${rec.severity} text-xs px-2 py-1 rounded-full font-semibold uppercase">
                                ${rec.severity}
                            </span>
                            ${rec.callEmergency ? '<span class="text-xs px-2 py-1 bg-red-600/20 border border-red-600/30 rounded-full font-semibold text-red-300">CALL ' + aiData.emergencyNumber + '</span>' : ''}
                        </div>
                        <p class="text-sm text-zinc-400 mb-2">${rec.summary}</p>
                        <div class="flex items-center gap-4 text-xs text-zinc-500">
                            <span><i class="fa-solid fa-list-ol mr-1"></i>${rec.immediateActions.length} immediate actions</span>
                            ${rec.callEmergency ? '<span><i class="fa-solid fa-phone-volume mr-1"></i>Emergency call required</span>' : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    // Add disclaimer
    if (aiData.disclaimer) {
        html += `
            <div class="p-3 bg-zinc-800/50 border-t border-zinc-800">
                <p class="text-xs text-zinc-500 text-center">
                    <i class="fa-solid fa-info-circle mr-1"></i>
                    ${aiData.disclaimer}
                </p>
            </div>
        `;
    }
    
    // Add close button at the bottom
    html += `
        <div class="p-3 border-t border-zinc-800">
            <button onclick="closeSearchResults()" class="w-full px-4 py-2 bg-zinc-700 hover:bg-zinc-600 rounded-lg font-semibold text-white transition-colors flex items-center justify-center gap-2">
                <i class="fa-solid fa-times"></i>
                Close Results
            </button>
        </div>
    `;
    
    searchResults.innerHTML = html;
    searchResults.classList.remove('hidden');
};

// Close search results
function closeSearchResults() {
    const searchResults = document.getElementById('searchResults');
    const searchInput = document.getElementById('emergencySearch');
    searchResults.innerHTML = '';
    searchResults.classList.add('hidden');
    searchInput.value = '';
}

window.performFallbackSearch = function(query) {
    const searchResults = document.getElementById('searchResults');
    if (!searchResults) return;
    
    console.log('Using fallback search for:', query);
    
    // Filter conditions based on query
    const results = window.CONDITIONS ? window.CONDITIONS.filter(condition => {
        const searchText = query.toLowerCase();
        return (
            condition.name.toLowerCase().includes(searchText) ||
            condition.summary.toLowerCase().includes(searchText) ||
            condition.category.toLowerCase().includes(searchText) ||
            (condition.steps && condition.steps.some(step => step.toLowerCase().includes(searchText)))
        );
    }) : [];
    
    window.displayEmergencySearchResults(results, query);
};

window.displayEmergencySearchResults = function(results, query) {
    const searchResults = document.getElementById('searchResults');
    if (!searchResults) return;
    
    if (results.length === 0) {
        searchResults.innerHTML = `
            <div class="p-4 text-center text-zinc-400">
                <i class="fa-solid fa-search mb-2 text-2xl"></i>
                <p>No emergencies found for "${query}". Try different keywords.</p>
            </div>
        `;
    } else {
      searchResults.innerHTML = results.slice(0, 5).map(condition => `
        <div class="p-4 border-b border-zinc-800 hover:bg-zinc-800/50 cursor-pointer transition-colors" onclick="goTo('guide')">
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0">
              <i class="fa-solid ${condition.icon} text-red-400"></i>
            </div>
            <div class="flex-1">
              <div class="flex items-center gap-2 mb-1">
                <h4 class="font-semibold text-white">${condition.name}</h4>
                <span class="sev-${condition.severity} text-xs px-2 py-1 rounded-full font-semibold uppercase">
                  ${condition.severity}
                </span>
              </div>
              <p class="text-sm text-zinc-400 mb-2">${condition.summary}</p>
              <div class="flex items-center gap-4 text-xs text-zinc-500">
                <span><i class="fa-solid fa-list-ol mr-1"></i>${condition.steps.length} steps</span>
                ${condition.call912 ? '<span><i class="fa-solid fa-phone-volume mr-1"></i>Call 912</span>' : ''}
              </div>
            </div>
          </div>
        </div>
      `).join('');
    }
    
    searchResults.classList.remove('hidden');
};

// Show AI recommendation details in modal
window.showAIRecommendationDetails = function(element) {
    try {
        const recommendation = JSON.parse(element.getAttribute('data-rec').replace(/&apos;/g, "'"));
        const aiInfo = JSON.parse(element.getAttribute('data-ai').replace(/&apos;/g, "'"));
        
        const severityColor = recommendation.severity === 'critical' ? 'red' : 
                             recommendation.severity === 'urgent' ? 'orange' : 
                             recommendation.severity === 'moderate' ? 'yellow' : 'green';
        
        const actionsHtml = recommendation.immediateActions.map((action, index) => 
            `<li class="flex gap-3 text-sm">
                <span class="w-6 h-6 rounded-full bg-${severityColor}-600/20 text-${severityColor}-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">${index + 1}</span>
                <span class="text-zinc-300">${action}</span>
            </li>`
        ).join('');
        
        const emergencySignsHtml = recommendation.emergencySigns ? 
            recommendation.emergencySigns.map(sign => 
                `<li class="text-xs text-zinc-300 flex items-center gap-2">
                    <i class="fa-solid fa-exclamation-triangle text-${severityColor}-400"></i>
                    ${sign}
                </li>`
            ).join('') : '';
        
        const modalHTML = `
            <div class="flex items-center justify-between p-5 border-b border-zinc-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-green-600/20 flex items-center justify-center">
                        <i class="fa-solid fa-brain text-green-400"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg">AI Emergency Recommendation</h3>
                        <span class="text-xs text-green-300">${aiInfo.aiPowered ? 'Powered by Google AI' : 'Fallback System'}</span>
                    </div>
                </div>
                <button onclick="closeModal()" class="w-8 h-8 rounded-lg hover:bg-zinc-800 flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-zinc-400"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="sev-${recommendation.severity} text-xs px-3 py-1 rounded-full font-semibold uppercase">
                        ${recommendation.severity}
                    </span>
                    ${recommendation.callEmergency ? `<span class="text-xs px-3 py-1 bg-red-600/20 border border-red-600/30 rounded-full font-semibold text-red-300">CALL ${aiInfo.emergencyNumber}</span>` : ''}
                </div>
                
                <h4 class="font-semibold text-white mb-2">${recommendation.condition}</h4>
                <p class="text-zinc-300 text-sm mb-5">${recommendation.summary}</p>
                
                <h4 class="font-semibold text-sm text-${severityColor}-400 mb-3">
                    <i class="fa-solid fa-list-ol mr-2"></i>Immediate Actions
                </h4>
                <ol class="space-y-2 mb-6">${actionsHtml}</ol>
                
                ${emergencySignsHtml ? `
                    <h4 class="font-semibold text-sm text-orange-400 mb-3">
                        <i class="fa-solid fa-exclamation-triangle mr-2"></i>Emergency Signs
                    </h4>
                    <ul class="space-y-1.5 mb-6">${emergencySignsHtml}</ul>
                ` : ''}
                
                <div class="p-4 rounded-lg bg-yellow-600/10 border border-yellow-600/20">
                    <p class="text-xs text-yellow-300">
                        <i class="fa-solid fa-info-circle mr-1"></i>
                        ${aiInfo.disclaimer}
                    </p>
                </div>
            </div>
        `;
        
        openModal(modalHTML);
    } catch (error) {
        console.error('Error showing AI recommendation details:', error);
    }
};

// Modal functionality
function openModal(content) {
    const modalBg = document.getElementById('modalBg');
    const modalBox = document.getElementById('modalBox');
    
    if (!modalBg || !modalBox) {
        console.error('Modal elements not found');
        return;
    }
    
    modalBox.innerHTML = content;
    modalBg.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const modalBg = document.getElementById('modalBg');
    
    if (!modalBg) {
        console.error('Modal background not found');
        return;
    }
    
    modalBg.classList.remove('open');
    document.body.style.overflow = 'auto';
}

// Body zone click handler - simplified version
function showZone(zone) {
    console.log('Body zone clicked:', zone);
    // Simple implementation - will be enhanced with database data
    showBodyMapTooltip(zone, 'Body map functionality will be enhanced with database data.');
}

// Comprehensive first aid conditions data for body map
const FALLBACK_CONDITIONS = {
    'body-wide': [
        {
            name: 'Severe Bleeding/Hemorrhage',
            severity: 'critical',
            icon: 'fa-droplet',
            summary: 'Life-threatening blood loss from any body part.',
            steps: [
                'Call 912 immediately - uncontrolled bleeding is life-threatening',
                'Apply direct, firm pressure with clean cloth or bandage',
                'Elevate injured body part above heart level if possible',
                'Apply pressure dressing if available',
                'Use tourniquet only for life-threatening limb bleeding',
                'Monitor for shock: pale skin, rapid pulse, fainting',
                'Keep person warm and calm, lie them down if dizzy'
            ],
            call912: true,
            dos: ['Apply direct pressure', 'Elevate bleeding area', 'Call 912 immediately', 'Monitor for shock'],
            donts: ['Do not remove embedded objects', 'Do not use tourniquet unless necessary', 'Do not give food/drink', 'Do not leave person alone']
        },
        {
            name: 'Shock',
            severity: 'critical',
            icon: 'fa-exclamation-triangle',
            summary: 'Life-threatening condition where organs don\'t get enough blood flow.',
            steps: [
                'Call 912 immediately - shock is a medical emergency',
                'Lay person flat on back, elevate legs 12 inches if no spinal injury',
                'Keep person warm with blankets or clothing',
                'Monitor breathing and consciousness continuously',
                'Loosen tight clothing around neck, chest, waist',
                'Do not give food or drink - surgery may be needed',
                'Treat any obvious bleeding while waiting for help'
            ],
            call912: true,
            dos: ['Call 912 immediately', 'Keep person warm', 'Elevate legs', 'Monitor vital signs'],
            donts: ['Do not give food/drink', 'Do not move if spinal injury suspected', 'Do not leave person alone', 'Do not ignore symptoms']
        },
        {
            name: 'Burns (Thermal)',
            severity: 'variable',
            icon: 'fa-fire',
            summary: 'Tissue damage from heat affecting any body area.',
            steps: [
                'Cool burn with cool (not ice) water for 15-20 minutes',
                'Remove jewelry, clothing, watches near burn area',
                'Cover with sterile, non-stick dressing',
                'Elevate burned area to reduce swelling',
                'Seek medical care for burns larger than palm, deep burns, or face/genital burns',
                'Do not apply ice, butter, ointments, or break blisters'
            ],
            call912: false,
            dos: ['Cool with water', 'Cover with dressing', 'Elevate area', 'Seek medical care for serious burns'],
            donts: ['Do not use ice', 'Do not apply butter/ointments', 'Do not break blisters', 'Do not use cotton balls']
        },
        {
            name: 'Chemical Burns',
            severity: 'urgent',
            icon: 'fa-flask',
            summary: 'Tissue damage from chemical exposure on any body part.',
            steps: [
                'Remove contaminated clothing immediately',
                'Flush affected area with cool running water for 15-20 minutes',
                'Brush off dry chemicals before flushing if powder',
                'Cover with sterile, non-stick dressing',
                'Identify chemical if possible for medical personnel',
                'Seek emergency care for face, eye, or large area burns',
                'Do not apply neutralizing agents unless specifically instructed'
            ],
            call912: false,
            dos: ['Flush with water immediately', 'Remove contaminated clothing', 'Cover with dressing', 'Identify chemical'],
            donts: ['Do not apply neutralizing agents', 'Do not use creams/ointments', 'Do not delay flushing', 'Do not ignore eye exposure']
        },
        {
            name: 'Electrical Burns',
            severity: 'critical',
            icon: 'fa-bolt',
            summary: 'Burns and internal damage from electrical current.',
            steps: [
                'Call 912 immediately - electrical burns are always serious',
                'Do not touch person until power is off',
                'Turn off power source or use non-conductive object to move person',
                'Check breathing and pulse, start CPR if needed',
                'Cover burn areas with sterile dressing',
                'Monitor for cardiac arrhythmias and internal injuries',
                'Treat both entry and exit wounds'
            ],
            call912: true,
            dos: ['Call 912 immediately', 'Ensure power is off', 'Check breathing/pulse', 'Cover entry/exit wounds'],
            donts: ['Do not touch person until power off', 'Do not underestimate internal damage', 'Do not delay medical care', 'Do not move person unnecessarily']
        },
        {
            name: 'Hypothermia',
            severity: 'critical',
            icon: 'fa-snowflake',
            summary: 'Dangerously low body temperature affecting whole body.',
            steps: [
                'Call 912 for severe hypothermia (confusion, slurred speech)',
                'Move person to warm, sheltered area',
                'Remove wet clothing, replace with dry layers',
                'Warm person gradually with blankets, body heat',
                'Apply warm compresses to neck, chest, groin',
                'Do not give alcohol, use warm non-alcoholic drinks if conscious',
                'Monitor breathing continuously'
            ],
            call912: true,
            dos: ['Warm gradually', 'Remove wet clothing', 'Use blankets', 'Monitor breathing'],
            donts: ['Do not warm too quickly', 'Do not give alcohol', 'Do not apply direct heat', 'Do not ignore symptoms']
        },
        {
            name: 'Heat Stroke',
            severity: 'critical',
            icon: 'fa-temperature-high',
            summary: 'Life-threatening body temperature emergency.',
            steps: [
                'Call 912 immediately - heat stroke is medical emergency',
                'Move person to cool, shaded area',
                'Remove excess clothing',
                'Cool with water, fans, ice packs to neck/armpits/groin',
                'Apply cool, wet cloths to skin',
                'Do not give alcohol, give cool water if conscious',
                'Monitor for seizures, confusion, loss of consciousness'
            ],
            call912: true,
            dos: ['Call 912 immediately', 'Cool rapidly', 'Remove clothing', 'Monitor consciousness'],
            donts: ['Do not give alcohol', 'Do not use ice baths', 'Do not delay cooling', 'Do not leave person alone']
        },
        {
            name: 'Allergic Reaction/Anaphylaxis',
            severity: 'critical',
            icon: 'fa-allergies',
            summary: 'Severe immune response affecting multiple body systems.',
            steps: [
                'Call 912 immediately for breathing difficulty or swelling',
                'Use epinephrine auto-injector if available',
                'Keep person calm and lying flat with legs elevated',
                'Loosen tight clothing',
                'Administer CPR if breathing stops',
                'Note time of epinephrine administration',
                'Monitor for second reaction (biphasic anaphylaxis)'
            ],
            call912: true,
            dos: ['Use epinephrine immediately', 'Call 912 for breathing issues', 'Keep calm', 'Monitor breathing'],
            donts: ['Do not delay epinephrine', 'Do not stand up or walk', 'Do not give food/drink', 'Do not leave person alone']
        },
        {
            name: 'Seizure',
            severity: 'urgent',
            icon: 'fa-bolt',
            summary: 'Uncontrolled electrical activity in brain affecting whole body.',
            steps: [
                'Call 912 if seizure lasts >5 minutes or repeats',
                'Protect person from injury - move objects away',
                'Place something soft under head if possible',
                'Do not restrain person or hold them down',
                'Time the seizure duration',
                'Turn person on side if possible (recovery position)',
                'Stay with person until fully awake and aware'
            ],
            call912: false,
            dos: ['Protect from injury', 'Time seizure', 'Place on side', 'Stay with person'],
            donts: ['Do not restrain person', 'Do not put anything in mouth', 'Do not give food/drink', 'Do not panic']
        },
        {
            name: 'Fainting/Syncope',
            severity: 'minor',
            icon: 'fa-face-dizzy',
            summary: 'Temporary loss of consciousness due to reduced blood flow to brain.',
            steps: [
                'Check for breathing and responsiveness',
                'Lay person flat on back, elevate legs 12 inches',
                'Loosen tight clothing around neck and waist',
                'Check for injuries from fall',
                'Ensure fresh air circulation',
                'Monitor for recovery within 1-2 minutes',
                'Call 912 if no recovery within 5 minutes or injury suspected'
            ],
            call912: false,
            dos: ['Lay flat, elevate legs', 'Check breathing', 'Loosen clothing', 'Monitor recovery'],
            donts: ['Do not sit person up', 'Do not slap or shake person', 'Do not give food/drink immediately', 'Do not ignore prolonged unconsciousness']
        },
        {
            name: 'Animal Bite',
            severity: 'urgent',
            icon: 'fa-dog',
            summary: 'Wound from animal bite with infection and rabies risk.',
            steps: [
                'Clean wound immediately with soap and water',
                'Apply gentle pressure to control bleeding',
                'Cover with sterile bandage',
                'Seek medical care for deep bites, hand/face bites, or unknown animal',
                'Report bite to animal control if needed',
                'Update tetanus vaccination if overdue',
                'Monitor for infection signs (redness, swelling, fever)'
            ],
            call912: false,
            dos: ['Clean wound thoroughly', 'Control bleeding', 'Seek medical care for serious bites', 'Update tetanus'],
            donts: ['Do not ignore animal bites', 'Do not delay cleaning', 'Do not assume no rabies risk', 'Do not ignore infection signs']
        },
        {
            name: 'Insect Sting/Bite',
            severity: 'variable',
            icon: 'fa-bug',
            summary: 'Reaction to insect venom affecting any body area.',
            steps: [
                'Remove stinger if present by scraping sideways',
                'Clean area with soap and water',
                'Apply cold compress to reduce pain and swelling',
                'Watch for allergic reaction (hives, swelling, difficulty breathing)',
                'Use hydrocortisone cream for mild local reactions',
                'Seek emergency care for severe reactions or multiple stings',
                'Take oral antihistamine if appropriate'
            ],
            call912: false,
            dos: ['Remove stinger', 'Clean area', 'Apply cold compress', 'Monitor for allergic reaction'],
            donts: ['Do not squeeze stinger', 'Do not scratch area', 'Do not ignore breathing difficulty', 'Do not delay emergency care for severe reactions']
        },
        {
            name: 'Poisoning/Overdose',
            severity: 'critical',
            icon: 'fa-skull-crossbones',
            summary: 'Exposure to toxic substances affecting whole body.',
            steps: [
                'Call 912 or poison control immediately',
                'Do NOT induce vomiting unless instructed',
                'Have person sip water if conscious and not having seizures',
                'Preserve container/bottle for identification',
                'Collect any vomit for medical analysis',
                'Monitor breathing and consciousness',
                'Follow specific instructions from poison control'
            ],
            call912: true,
            dos: ['Call poison control/912', 'Preserve container', 'Monitor breathing', 'Follow professional instructions'],
            donts: ['Do not induce vomiting', 'Do not give food/drink', 'Do not delay calling for help', 'Do not ignore symptoms']
        },
        {
            name: 'Choking/Suffocation',
            severity: 'critical',
            icon: 'fa-lungs',
            summary: 'Airway obstruction preventing breathing.',
            steps: [
                'Ask "Are you choking?" - if they can speak/cough, encourage coughing',
                'If unable to speak/breathe, perform Heimlich maneuver',
                'Stand behind person, make fist above navel, thrust inward and upward',
                'Continue thrustes until object expelled or person unconscious',
                'If unconscious, begin CPR and call 912',
                'Check mouth for object after each set of CPR compressions',
                'Continue until medical help arrives'
            ],
            call912: true,
            dos: ['Perform Heimlich maneuver', 'Call 912 if unsuccessful', 'Begin CPR if unconscious', 'Continue until help arrives'],
            donts: ['Do not slap person on back (standing)', 'Do not give water', 'Do not panic', 'Do not delay calling 912 if needed']
        },
        {
            name: 'Drowning',
            severity: 'critical',
            icon: 'fa-water',
            summary: 'Breathing impairment from water in lungs.',
            steps: [
                'Call 912 immediately - drowning is always emergency',
                'Remove person from water safely',
                'Check for breathing and pulse',
                'Begin CPR if no breathing (30 compressions:2 breaths)',
                'Place person on back, tilt head back, lift chin',
                'Give rescue breaths, watch for chest rise',
                'Continue CPR until person breathes or help arrives',
                'Remove wet clothing, keep person warm'
            ],
            call912: true,
            dos: ['Call 912 immediately', 'Begin CPR if needed', 'Remove from water safely', 'Keep person warm'],
            donts: ['Do not delay CPR', 'Do not waste time removing water from lungs', 'Do not leave person alone', 'Do not assume person is fine']
        }
    ],
    'head': [
        {
            name: 'Head Trauma/Concussion',
            severity: 'critical',
            icon: 'fa-brain',
            summary: 'Traumatic brain injury from impact to the head.',
            steps: [
                'Call 912 if loss of consciousness, confusion, or severe headache',
                'Monitor breathing and consciousness continuously',
                'Apply cold compress to reduce swelling (20 min on, 20 min off)',
                'Keep head and neck immobilized if spinal injury suspected',
                'Do not give food or drink in case surgery is needed',
                'Keep person awake and alert if conscious',
                'Note any changes in condition for medical personnel'
            ],
            call912: true,
            dos: ['Monitor vital signs', 'Keep person calm', 'Use cold compress', 'Document symptoms'],
            donts: ['Do not leave person alone', 'Do not give medication', 'Do not move head/neck if injured', 'Do not allow sleep if serious symptoms']
        },
        {
            name: 'Eye Injury',
            severity: 'urgent',
            icon: 'fa-eye',
            summary: 'Foreign object, chemical splash, or trauma to the eye.',
            steps: [
                'For chemicals: Flush eye with clean water for 15 minutes',
                'For foreign objects: Do not rub - let tears wash it out',
                'For cuts: Cover with clean, sterile dressing',
                'Keep both eyes closed to prevent movement',
                'Seek immediate medical attention for vision changes',
                'Do not attempt to remove embedded objects'
            ],
            call912: false,
            dos: ['Flush chemical exposures', 'Cover both eyes', 'Keep person calm', 'Seek medical help promptly'],
            donts: ['Do not rub eyes', 'Do not apply pressure', 'Do not use eye drops', 'Do not remove embedded objects']
        },
        {
            name: 'Nosebleed',
            severity: 'minor',
            icon: 'fa-droplet',
            summary: 'Bleeding from the nose, common and usually not serious.',
            steps: [
                'Sit upright and lean forward slightly',
                'Pinch soft part of nose firmly for 10-15 minutes',
                'Apply cold compress to bridge of nose',
                'Breathe through mouth during pinching',
                'Avoid blowing nose or strenuous activity for 24 hours',
                'Seek medical help if bleeding continues >20 minutes'
            ],
            call912: false,
            dos: ['Sit upright', 'Lean forward', 'Pinch nose firmly', 'Apply cold compress'],
            donts: ['Do not tilt head back', 'Do not blow nose', 'Do not insert tissues deep in nose', 'Do not lie down']
        }
    ],
    'chest': [
        {
            name: 'Heart Attack',
            severity: 'critical',
            icon: 'fa-heart',
            summary: 'Blockage of blood flow to the heart muscle.',
            steps: [
                'Call 912 immediately - every minute counts',
                'Give aspirin (325mg) if available and not allergic',
                'Keep person calm and in comfortable position',
                'Loosen tight clothing around neck and chest',
                'Monitor breathing and pulse continuously',
                'Be prepared to perform CPR if cardiac arrest occurs',
                'Note onset time for medical personnel'
            ],
            call912: true,
            dos: ['Call 912 immediately', 'Give aspirin', 'Keep calm', 'Monitor vital signs'],
            donts: ['Do not give food or drink', 'Do not leave person alone', 'Do not allow exertion', 'Do not delay calling 912']
        },
        {
            name: 'Rib Fracture',
            severity: 'moderate',
            icon: 'fa-bone',
            summary: 'Broken rib causing pain with breathing.',
            steps: [
                'Apply cold compress to reduce swelling and pain',
                'Support injured area with pillow when coughing',
                'Encourage shallow breathing to prevent lung collapse',
                'Seek medical evaluation for proper diagnosis',
                'Monitor for difficulty breathing',
                'Pain management with approved medications only'
            ],
            call912: false,
            dos: ['Apply cold pack', 'Support with pillow', 'Encourage deep breathing', 'Seek medical care'],
            donts: ['Do not wrap chest tightly', 'Do not ignore breathing difficulty', 'Do not apply heat initially', 'Do not engage in strenuous activity']
        },
        {
            name: 'Pneumothorax (Collapsed Lung)',
            severity: 'critical',
            icon: 'fa-lungs',
            summary: 'Air in chest cavity causing lung collapse.',
            steps: [
                'Call 912 immediately - medical emergency',
                'Keep person sitting upright if possible',
                'Monitor breathing and oxygen levels',
                'Do not apply pressure to chest',
                'Prepare for possible chest tube insertion',
                'Keep person calm and still'
            ],
            call912: true,
            dos: ['Call 912 immediately', 'Keep upright position', 'Monitor breathing', 'Stay calm'],
            donts: ['Do not apply chest pressure', 'Do not allow exertion', 'Do not give food/drink', 'Do not delay medical care']
        }
    ],
    'abdomen': [
        {
            name: 'Internal Bleeding',
            severity: 'critical',
            icon: 'fa-droplet',
            summary: 'Bleeding inside abdominal cavity from trauma.',
            steps: [
                'Call 912 immediately - life-threatening emergency',
                'Keep person lying flat with knees bent',
                'Apply cold packs to abdomen if trauma suspected',
                'Monitor for shock (pale, rapid pulse, fainting)',
                'Do not give food or drink - surgery may be needed',
                'Cover with blanket to maintain body temperature',
                'Monitor vital signs continuously'
            ],
            call912: true,
            dos: ['Call 912 immediately', 'Keep lying down', 'Apply cold packs', 'Monitor for shock'],
            donts: ['Do not give food/drink', 'Do not apply pressure', 'Do not allow movement', 'Do not delay medical care']
        },
        {
            name: 'Appendicitis',
            severity: 'urgent',
            icon: 'fa-stomach',
            summary: 'Inflammation of appendix requiring surgery.',
            steps: [
                'Call 912 or go to emergency department',
                'Do not give food or drink - surgery may be needed',
                'Apply cold compress to abdomen for pain',
                'Keep person lying on left side if more comfortable',
                'Monitor for fever, vomiting, increased pain',
                'Note pain location and onset time'
            ],
            call912: false,
            dos: ['Seek medical care', 'Apply cold compress', 'Keep NPO (nothing by mouth)', 'Monitor symptoms'],
            donts: ['Do not give food/drink', 'Do not apply heat', 'Do not give pain medication', 'Do not delay seeking care']
        },
        {
            name: 'Abdominal Wound',
            severity: 'urgent',
            icon: 'fa-bandage',
            summary: 'Cut or penetrating injury to abdomen.',
            steps: [
                'Call 912 for deep or penetrating wounds',
                'Apply direct pressure with clean cloth',
                'Do not remove embedded objects',
                'Cover wound with sterile dressing',
                'Monitor for signs of internal bleeding',
                'Keep person lying down with knees bent'
            ],
            call912: false,
            dos: ['Apply direct pressure', 'Cover wound', 'Monitor bleeding', 'Keep comfortable position'],
            donts: ['Do not remove objects', 'Do not probe wound', 'Do not apply excessive pressure', 'Do not give food/drink']
        }
    ],
    'left-arm': [
        {
            name: 'Fracture/Dislocation',
            severity: 'urgent',
            icon: 'fa-bone',
            summary: 'Broken bone or joint out of socket.',
            steps: [
                'Immobilize arm in position found',
                'Apply splint above and below injury site',
                'Apply cold pack to reduce swelling',
                'Elevate arm above heart level if possible',
                'Seek medical evaluation promptly',
                'Do not attempt to straighten or reset',
                'Check circulation below injury'
            ],
            call912: false,
            dos: ['Immobilize immediately', 'Apply cold pack', 'Elevate arm', 'Seek medical care'],
            donts: ['Do not straighten limb', 'Do not move joint', 'Do not apply heat', 'Do not allow weight bearing']
        },
        {
            name: 'Deep Cut/Laceration',
            severity: 'moderate',
            icon: 'fa-cut',
            summary: 'Deep cut requiring possible stitches.',
            steps: [
                'Apply direct pressure with clean cloth',
                'Elevate arm above heart level',
                'Clean wound with sterile saline if available',
                'Cover with sterile dressing',
                'Seek medical evaluation for stitches',
                'Monitor for continued bleeding'
            ],
            call912: false,
            dos: ['Apply direct pressure', 'Elevate limb', 'Clean wound', 'Cover dressing'],
            donts: ['Do not remove embedded objects', 'Do not apply tourniquet unless severe', 'Do not ignore deep cuts', 'Do not delay medical care']
        },
        {
            name: 'Burn Injury',
            severity: 'moderate',
            icon: 'fa-fire',
            summary: 'Thermal, chemical, or electrical burn.',
            steps: [
                'Cool burn with cool (not cold) water for 15-20 minutes',
                'Remove jewelry/tight clothing from burned area',
                'Cover with sterile, non-stick dressing',
                'Elevate arm to reduce swelling',
                'Seek medical care for large or deep burns',
                'Do not apply ice, butter, or ointments'
            ],
            call912: false,
            dos: ['Cool with water', 'Cover with dressing', 'Elevate limb', 'Seek medical care for serious burns'],
            donts: ['Do not use ice', 'Do not apply butter/ointments', 'Do not break blisters', 'Do not use cotton balls']
        }
    ],
    'right-arm': [
        {
            name: 'Fracture/Dislocation',
            severity: 'urgent',
            icon: 'fa-bone',
            summary: 'Broken bone or joint out of socket.',
            steps: [
                'Immobilize arm in position found',
                'Apply splint above and below injury site',
                'Apply cold pack to reduce swelling',
                'Elevate arm above heart level if possible',
                'Seek medical evaluation promptly',
                'Do not attempt to straighten or reset',
                'Check circulation below injury'
            ],
            call912: false,
            dos: ['Immobilize immediately', 'Apply cold pack', 'Elevate arm', 'Seek medical care'],
            donts: ['Do not straighten limb', 'Do not move joint', 'Do not apply heat', 'Do not allow weight bearing']
        },
        {
            name: 'Deep Cut/Laceration',
            severity: 'moderate',
            icon: 'fa-cut',
            summary: 'Deep cut requiring possible stitches.',
            steps: [
                'Apply direct pressure with clean cloth',
                'Elevate arm above heart level',
                'Clean wound with sterile saline if available',
                'Cover with sterile dressing',
                'Seek medical evaluation for stitches',
                'Monitor for continued bleeding'
            ],
            call912: false,
            dos: ['Apply direct pressure', 'Elevate limb', 'Clean wound', 'Cover dressing'],
            donts: ['Do not remove embedded objects', 'Do not apply tourniquet unless severe', 'Do not ignore deep cuts', 'Do not delay medical care']
        },
        {
            name: 'Burn Injury',
            severity: 'moderate',
            icon: 'fa-fire',
            summary: 'Thermal, chemical, or electrical burn.',
            steps: [
                'Cool burn with cool (not cold) water for 15-20 minutes',
                'Remove jewelry/tight clothing from burned area',
                'Cover with sterile, non-stick dressing',
                'Elevate arm to reduce swelling',
                'Seek medical care for large or deep burns',
                'Do not apply ice, butter, or ointments'
            ],
            call912: false,
            dos: ['Cool with water', 'Cover with dressing', 'Elevate limb', 'Seek medical care for serious burns'],
            donts: ['Do not use ice', 'Do not apply butter/ointments', 'Do not break blisters', 'Do not use cotton balls']
        }
    ],
    'left-leg': [
        {
            name: 'Fracture',
            severity: 'urgent',
            icon: 'fa-bone',
            summary: 'Broken bone in leg requiring immobilization.',
            steps: [
                'Do not move person unless in danger',
                'Immobilize leg in position found',
                'Apply splint above and below fracture',
                'Apply cold pack to reduce swelling',
                'Check circulation below injury (pulse, color)',
                'Call 912 if unable to immobilize properly',
                'Monitor for shock symptoms'
            ],
            call912: false,
            dos: ['Immobilize immediately', 'Apply splint', 'Apply cold pack', 'Check circulation'],
            donts: ['Do not straighten limb', 'Do not allow weight bearing', 'Do not move unnecessarily', 'Do not ignore circulation changes']
        },
        {
            name: 'Deep Vein Thrombosis (DVT)',
            severity: 'urgent',
            icon: 'fa-vein',
            summary: 'Blood clot in deep vein, usually in calf.',
            steps: [
                'Seek immediate medical evaluation',
                'Keep leg elevated when resting',
                'Apply warm compress to affected area',
                'Do not massage the affected area',
                'Monitor for shortness of breath (pulmonary embolism)',
                'Take prescribed blood thinners as directed'
            ],
            call912: false,
            dos: ['Seek medical care', 'Elevate leg', 'Apply warm compress', 'Monitor breathing'],
            donts: ['Do not massage area', 'Do not ignore symptoms', 'Do not delay medical care', 'Do not allow prolonged sitting']
        },
        {
            name: 'Severe Sprain',
            severity: 'moderate',
            icon: 'fa-bandage',
            summary: 'Ligament tear causing pain and swelling.',
            steps: [
                'Apply RICE: Rest, Ice, Compression, Elevation',
                'Ice for 15-20 minutes every 2-3 hours',
                'Compress with elastic bandage',
                'Elevate above heart level',
                'Seek medical evaluation for severe sprains',
                'Avoid weight bearing for 24-48 hours'
            ],
            call912: false,
            dos: ['Apply RICE protocol', 'Ice regularly', 'Compress properly', 'Elevate limb'],
            donts: ['Do not apply heat initially', 'Do not bear weight', 'Do not ignore severe pain', 'Do not delay medical evaluation']
        }
    ],
    'right-leg': [
        {
            name: 'Fracture',
            severity: 'urgent',
            icon: 'fa-bone',
            summary: 'Broken bone in leg requiring immobilization.',
            steps: [
                'Do not move person unless in danger',
                'Immobilize leg in position found',
                'Apply splint above and below fracture',
                'Apply cold pack to reduce swelling',
                'Check circulation below injury (pulse, color)',
                'Call 912 if unable to immobilize properly',
                'Monitor for shock symptoms'
            ],
            call912: false,
            dos: ['Immobilize immediately', 'Apply splint', 'Apply cold pack', 'Check circulation'],
            donts: ['Do not straighten limb', 'Do not allow weight bearing', 'Do not move unnecessarily', 'Do not ignore circulation changes']
        },
        {
            name: 'Deep Vein Thrombosis (DVT)',
            severity: 'urgent',
            icon: 'fa-vein',
            summary: 'Blood clot in deep vein, usually in calf.',
            steps: [
                'Seek immediate medical evaluation',
                'Keep leg elevated when resting',
                'Apply warm compress to affected area',
                'Do not massage the affected area',
                'Monitor for shortness of breath (pulmonary embolism)',
                'Take prescribed blood thinners as directed'
            ],
            call912: false,
            dos: ['Seek medical care', 'Elevate leg', 'Apply warm compress', 'Monitor breathing'],
            donts: ['Do not massage area', 'Do not ignore symptoms', 'Do not delay medical care', 'Do not allow prolonged sitting']
        },
        {
            name: 'Severe Sprain',
            severity: 'moderate',
            icon: 'fa-bandage',
            summary: 'Ligament tear causing pain and swelling.',
            steps: [
                'Apply RICE: Rest, Ice, Compression, Elevation',
                'Ice for 15-20 minutes every 2-3 hours',
                'Compress with elastic bandage',
                'Elevate above heart level',
                'Seek medical evaluation for severe sprains',
                'Avoid weight bearing for 24-48 hours'
            ],
            call912: false,
            dos: ['Apply RICE protocol', 'Ice regularly', 'Compress properly', 'Elevate limb'],
            donts: ['Do not apply heat initially', 'Do not bear weight', 'Do not ignore severe pain', 'Do not delay medical evaluation']
        }
    ],
    'left-hand': [
        {
            name: 'Hand Fracture',
            severity: 'urgent',
            icon: 'fa-bone',
            summary: 'Broken bone in hand or fingers.',
            steps: [
                'Immobilize hand with splint or buddy taping',
                'Apply cold pack to reduce swelling',
                'Elevate hand above heart level',
                'Remove rings, watches, bracelets',
                'Seek medical evaluation for proper alignment',
                'Check circulation and sensation in fingers'
            ],
            call912: false,
            dos: ['Immobilize fingers', 'Apply cold pack', 'Elevate hand', 'Remove jewelry'],
            donts: ['Do not try to straighten fingers', 'Do not ignore swelling', 'Do not delay medical care', 'Do not use hand']
        },
        {
            name: 'Severe Cut/Amputation',
            severity: 'critical',
            icon: 'fa-cut',
            summary: 'Deep cut or partial/complete finger amputation.',
            steps: [
                'Call 912 immediately for amputation',
                'Apply direct pressure with clean cloth',
                'Elevate hand above heart level',
                'For amputated part: Wrap in gauze, place in sealed bag',
                'Keep amputated part cool (not frozen)',
                'Do not apply tourniquet unless life-threatening bleeding',
                'Monitor for shock symptoms'
            ],
            call912: true,
            dos: ['Call 912 for amputation', 'Apply direct pressure', 'Elevate hand', 'Preserve amputated part'],
            donts: ['Do not apply tourniquet unnecessarily', 'Do not clean amputated part', 'Do not freeze amputated part', 'Do not delay emergency care']
        },
        {
            name: 'Burn/Chemical Exposure',
            severity: 'moderate',
            icon: 'fa-fire',
            summary: 'Thermal burn or chemical exposure to hand.',
            steps: [
                'For chemicals: Flush with cool water 15+ minutes',
                'For burns: Cool with water (not ice) 15-20 minutes',
                'Remove jewelry and contaminated clothing',
                'Cover with sterile, non-stick dressing',
                'Elevate hand to reduce swelling',
                'Seek medical care for severe burns'
            ],
            call912: false,
            dos: ['Flush chemicals thoroughly', 'Cool burns with water', 'Cover with dressing', 'Elevate hand'],
            donts: ['Do not use ice on burns', 'Do not apply butter/ointments', 'Do not break blisters', 'Do not ignore chemical exposure']
        }
    ],
    'right-hand': [
        {
            name: 'Hand Fracture',
            severity: 'urgent',
            icon: 'fa-bone',
            summary: 'Broken bone in hand or fingers.',
            steps: [
                'Immobilize hand with splint or buddy taping',
                'Apply cold pack to reduce swelling',
                'Elevate hand above heart level',
                'Remove rings, watches, bracelets',
                'Seek medical evaluation for proper alignment',
                'Check circulation and sensation in fingers'
            ],
            call912: false,
            dos: ['Immobilize fingers', 'Apply cold pack', 'Elevate hand', 'Remove jewelry'],
            donts: ['Do not try to straighten fingers', 'Do not ignore swelling', 'Do not delay medical care', 'Do not use hand']
        },
        {
            name: 'Severe Cut/Amputation',
            severity: 'critical',
            icon: 'fa-cut',
            summary: 'Deep cut or partial/complete finger amputation.',
            steps: [
                'Call 912 immediately for amputation',
                'Apply direct pressure with clean cloth',
                'Elevate hand above heart level',
                'For amputated part: Wrap in gauze, place in sealed bag',
                'Keep amputated part cool (not frozen)',
                'Do not apply tourniquet unless life-threatening bleeding',
                'Monitor for shock symptoms'
            ],
            call912: true,
            dos: ['Call 912 for amputation', 'Apply direct pressure', 'Elevate hand', 'Preserve amputated part'],
            donts: ['Do not apply tourniquet unnecessarily', 'Do not clean amputated part', 'Do not freeze amputated part', 'Do not delay emergency care']
        },
        {
            name: 'Burn/Chemical Exposure',
            severity: 'moderate',
            icon: 'fa-fire',
            summary: 'Thermal burn or chemical exposure to hand.',
            steps: [
                'For chemicals: Flush with cool water 15+ minutes',
                'For burns: Cool with water (not ice) 15-20 minutes',
                'Remove jewelry and contaminated clothing',
                'Cover with sterile, non-stick dressing',
                'Elevate hand to reduce swelling',
                'Seek medical care for severe burns'
            ],
            call912: false,
            dos: ['Flush chemicals thoroughly', 'Cool burns with water', 'Cover with dressing', 'Elevate hand'],
            donts: ['Do not use ice on burns', 'Do not apply butter/ointments', 'Do not break blisters', 'Do not ignore chemical exposure']
        }
    ],
    'left-foot': [
        {
            name: 'Fracture',
            severity: 'urgent',
            icon: 'fa-bone',
            summary: 'Broken bone in foot or ankle.',
            steps: [
                'Do not move person unless necessary',
                'Immobilize foot in position found',
                'Apply cold pack to reduce swelling',
                'Remove shoe and sock if safe to do so',
                'Elevate foot above heart level',
                'Seek medical evaluation for proper treatment',
                'Check circulation to toes'
            ],
            call912: false,
            dos: ['Immobilize foot', 'Apply cold pack', 'Elevate foot', 'Remove footwear carefully'],
            donts: ['Do not try to straighten', 'Do not allow weight bearing', 'Do not ignore circulation changes', 'Do not delay medical care']
        },
        {
            name: 'Severe Sprain/Ankle Injury',
            severity: 'moderate',
            icon: 'fa-bandage',
            summary: 'Ligament damage causing pain and swelling.',
            steps: [
                'Apply RICE: Rest, Ice, Compression, Elevation',
                'Ice for 15-20 minutes every 2-3 hours',
                'Compress with elastic bandage from toes upward',
                'Elevate foot above heart level',
                'Avoid weight bearing for 24-48 hours',
                'Seek medical evaluation for severe sprains'
            ],
            call912: false,
            dos: ['Apply RICE protocol', 'Ice regularly', 'Compress properly', 'Elevate foot'],
            donts: ['Do not apply heat initially', 'Do not bear weight', 'Do not ignore severe pain', 'Do not delay medical evaluation']
        },
        {
            name: 'Frostbite',
            severity: 'moderate',
            icon: 'fa-snowflake',
            summary: 'Freezing of body tissue causing loss of feeling.',
            steps: [
                'Get to warm environment immediately',
                'Remove wet clothing and constrictive items',
                'Warm foot in warm water (104-108°F) for 15-30 minutes',
                'Do not rub or massage frozen area',
                'Protect from refreezing',
                'Seek medical evaluation for severe frostbite',
                'Monitor for tissue damage'
            ],
            call912: false,
            dos: ['Warm gradually', 'Use warm water', 'Protect from refreezing', 'Seek medical care'],
            donts: ['Do not rub frozen area', 'Do not use hot water', 'Do not walk on frozen foot', 'Do not break blisters']
        },
        {
            name: 'Puncture Wound',
            severity: 'moderate',
            icon: 'fa-syringe',
            summary: 'Deep wound from sharp object penetration.',
            steps: [
                'Clean wound with sterile saline if available',
                'Apply direct pressure if bleeding',
                'Do not remove embedded objects',
                'Cover with sterile dressing',
                'Elevate foot to reduce swelling',
                'Seek medical evaluation for tetanus shot',
                'Monitor for infection signs'
            ],
            call912: false,
            dos: ['Clean wound', 'Apply pressure if bleeding', 'Cover with dressing', 'Elevate foot'],
            donts: ['Do not remove embedded objects', 'Do not ignore deep punctures', 'Do not delay tetanus evaluation', 'Do not allow weight bearing']
        }
    ],
    'right-foot': [
        {
            name: 'Fracture',
            severity: 'urgent',
            icon: 'fa-bone',
            summary: 'Broken bone in foot or ankle.',
            steps: [
                'Do not move person unless necessary',
                'Immobilize foot in position found',
                'Apply cold pack to reduce swelling',
                'Remove shoe and sock if safe to do so',
                'Elevate foot above heart level',
                'Seek medical evaluation for proper treatment',
                'Check circulation to toes'
            ],
            call912: false,
            dos: ['Immobilize foot', 'Apply cold pack', 'Elevate foot', 'Remove footwear carefully'],
            donts: ['Do not try to straighten', 'Do not allow weight bearing', 'Do not ignore circulation changes', 'Do not delay medical care']
        },
        {
            name: 'Severe Sprain/Ankle Injury',
            severity: 'moderate',
            icon: 'fa-bandage',
            summary: 'Ligament damage causing pain and swelling.',
            steps: [
                'Apply RICE: Rest, Ice, Compression, Elevation',
                'Ice for 15-20 minutes every 2-3 hours',
                'Compress with elastic bandage from toes upward',
                'Elevate foot above heart level',
                'Avoid weight bearing for 24-48 hours',
                'Seek medical evaluation for severe sprains'
            ],
            call912: false,
            dos: ['Apply RICE protocol', 'Ice regularly', 'Compress properly', 'Elevate foot'],
            donts: ['Do not apply heat initially', 'Do not bear weight', 'Do not ignore severe pain', 'Do not delay medical evaluation']
        },
        {
            name: 'Frostbite',
            severity: 'moderate',
            icon: 'fa-snowflake',
            summary: 'Freezing of body tissue causing loss of feeling.',
            steps: [
                'Get to warm environment immediately',
                'Remove wet clothing and constrictive items',
                'Warm foot in warm water (104-108°F) for 15-30 minutes',
                'Do not rub or massage frozen area',
                'Protect from refreezing',
                'Seek medical evaluation for severe frostbite',
                'Monitor for tissue damage'
            ],
            call912: false,
            dos: ['Warm gradually', 'Use warm water', 'Protect from refreezing', 'Seek medical care'],
            donts: ['Do not rub frozen area', 'Do not use hot water', 'Do not walk on frozen foot', 'Do not break blisters']
        },
        {
            name: 'Puncture Wound',
            severity: 'moderate',
            icon: 'fa-syringe',
            summary: 'Deep wound from sharp object penetration.',
            steps: [
                'Clean wound with sterile saline if available',
                'Apply direct pressure if bleeding',
                'Do not remove embedded objects',
                'Cover with sterile dressing',
                'Elevate foot to reduce swelling',
                'Seek medical evaluation for tetanus shot',
                'Monitor for infection signs'
            ],
            call912: false,
            dos: ['Clean wound', 'Apply pressure if bleeding', 'Cover with dressing', 'Elevate foot'],
            donts: ['Do not remove embedded objects', 'Do not ignore deep punctures', 'Do not delay tetanus evaluation', 'Do not allow weight bearing']
        }
    ]
};

// Body zone click handler
function showZone(zone) {
    console.log('Body zone clicked:', zone);
    
    // Use fallback data immediately - this will work every time
    const relevantConditions = FALLBACK_CONDITIONS[zone] || [];
    
    if (relevantConditions.length === 0) {
        // Show a simple tooltip for zones without data
        showBodyMapTooltip(zone, 'No specific conditions found for this area.');
        return;
    }
    
    // Display zone-specific conditions using fallback data
    displayZoneConditions(zone, relevantConditions);
}

function showZoneFallback(zone) {
    // Show a simple tooltip for fallback case
    showBodyMapTooltip(zone, 'Loading first aid information...');
}

function displayZoneConditions(zone, conditions) {
    // Clear any existing tooltips
    clearBodyMapTooltips();
    
    if (conditions.length === 0) {
        // Show a simple tooltip saying no conditions found
        showBodyMapTooltip(zone, 'No specific conditions found for this area.');
        return;
    }
    
    // Show the first condition as a tooltip on the body map
    const primaryCondition = conditions[0];
    const tooltipText = `${primaryCondition.name}: ${primaryCondition.steps.slice(0, 2).join('. ')}.`;
    showBodyMapTooltip(zone, tooltipText);
    
    // Also update the zone panel with full details
    updateZonePanel(zone, conditions);
}

function showBodyMapTooltip(zone, text) {
    const bodyMapSvg = document.querySelector('#pg-bodymap svg');
    if (!bodyMapSvg) return;
    
    // Get position for tooltip based on body zone
    const position = getTooltipPosition(zone);
    
    // Create tooltip element
    const tooltip = document.createElement('div');
    tooltip.className = 'body-map-tooltip';
    tooltip.style.cssText = `
        position: absolute;
        left: ${position.x}px;
        top: ${position.y}px;
        background: rgba(239, 68, 68, 0.95);
        color: white;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        max-width: 200px;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        pointer-events: none;
        animation: fadeIn 0.3s ease-in-out;
    `;
    tooltip.textContent = text;
    
    // Add to body map container
    bodyMapSvg.parentElement.style.position = 'relative';
    bodyMapSvg.parentElement.appendChild(tooltip);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        if (tooltip.parentElement) {
            tooltip.remove();
        }
    }, 5000);
}

function getTooltipPosition(zone) {
    // SVG viewBox is 0 0 200 440, but displayed at 220x480
    const scaleX = 220 / 200;
    const scaleY = 480 / 440;
    
    const positions = {
        'head': { x: 100 * scaleX - 60, y: 42 * scaleY - 30 },
        'chest': { x: 100 * scaleX - 60, y: 112 * scaleY - 20 },
        'abdomen': { x: 100 * scaleX - 60, y: 168 * scaleY - 20 },
        'left-arm': { x: 42 * scaleX - 80, y: 134 * scaleY - 20 },
        'right-arm': { x: 158 * scaleX + 20, y: 134 * scaleY - 20 },
        'left-leg': { x: 65 * scaleX - 80, y: 296 * scaleY - 20 },
        'right-leg': { x: 135 * scaleX + 20, y: 296 * scaleY - 20 },
        'left-hand': { x: 28 * scaleX - 80, y: 178 * scaleY - 20 },
        'right-hand': { x: 172 * scaleX + 20, y: 178 * scaleY - 20 },
        'left-foot': { x: 65 * scaleX - 80, y: 390 * scaleY - 20 },
        'right-foot': { x: 135 * scaleX + 20, y: 390 * scaleY - 20 }
    };
    
    return positions[zone] || { x: 100, y: 100 };
}

function updateZonePanel(zone, conditions) {
    const zonePanel = document.getElementById('zonePanel');
    if (!zonePanel) return;
    
    let conditionsHTML = `
        <div class="mb-6">
            <h3 class="text-xl font-bold text-white mb-2">${getZoneDisplayName(zone)} First Aid Guide</h3>
            <p class="text-zinc-400 mb-4">Comprehensive first aid for injuries and conditions affecting this area</p>
        </div>
        <div class="space-y-4">
    `;
    
    conditions.forEach(condition => {
        const hasDos = condition.dos && condition.dos.length > 0;
        const hasDonts = condition.donts && condition.donts.length > 0;
        
        conditionsHTML += `
            <div class="bg-zinc-800/50 border border-zinc-700 rounded-xl p-4 hover:border-red-600/50 transition-all">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg bg-red-600/20 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid ${condition.icon} text-red-400"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <h4 class="font-semibold text-white">${condition.name}</h4>
                            <span class="sev-${condition.severity} text-xs px-2 py-1 rounded-full font-semibold uppercase">
                                ${condition.severity}
                            </span>
                            ${condition.call912 ? '<span class="text-xs px-2 py-1 rounded-full bg-red-600/20 text-red-400 border border-red-600/30 font-semibold">Call 912</span>' : ''}
                        </div>
                        <p class="text-zinc-300 text-sm mb-3">${condition.summary}</p>
                        
                        <div class="mb-3">
                            <h5 class="text-xs font-semibold text-white mb-2">
                                <i class="fa-solid fa-list-ol mr-1 text-red-400"></i>Emergency Steps:
                            </h5>
                            <ol class="text-xs text-zinc-300 space-y-1">
                                ${condition.steps.map((step, index) => 
                                    `<li class="flex gap-2">
                                        <span class="text-red-400 font-semibold flex-shrink-0">${index + 1}.</span>
                                        <span>${step}</span>
                                    </li>`
                                ).join('')}
                            </ol>
                        </div>
                        
                        ${hasDos ? `
                        <div class="mb-3 p-3 rounded-lg bg-green-600/5 border border-green-600/15">
                            <h5 class="text-xs font-bold text-green-400 mb-2">
                                <i class="fa-solid fa-check mr-1"></i> DO:
                            </h5>
                            <ul class="text-xs text-zinc-300 space-y-1">
                                ${condition.dos.map(item => 
                                    `<li class="flex gap-2">
                                        <i class="fa-solid fa-check text-green-400 text-xs mt-0.5 flex-shrink-0"></i>
                                        <span>${item}</span>
                                    </li>`
                                ).join('')}
                            </ul>
                        </div>
                        ` : ''}
                        
                        ${hasDonts ? `
                        <div class="mb-3 p-3 rounded-lg bg-red-600/5 border border-red-600/15">
                            <h5 class="text-xs font-bold text-red-400 mb-2">
                                <i class="fa-solid fa-xmark mr-1"></i> DON'T:
                            </h5>
                            <ul class="text-xs text-zinc-300 space-y-1">
                                ${condition.donts.map(item => 
                                    `<li class="flex gap-2">
                                        <i class="fa-solid fa-xmark text-red-400 text-xs mt-0.5 flex-shrink-0"></i>
                                        <span>${item}</span>
                                    </li>`
                                ).join('')}
                            </ul>
                        </div>
                        ` : ''}
                        
                        <div class="flex gap-2 flex-wrap">
                            <!-- Emergency Call button removed -->
                            <button onclick="showConditionTooltip('${zone}', '${condition.name.replace(/'/g, "\\'")}', '${condition.steps[0].replace(/'/g, "\\'")}')" class="px-3 py-2 bg-zinc-600 hover:bg-zinc-500 text-white rounded text-xs font-semibold transition-colors">
                                <i class="fa-solid fa-info-circle mr-1"></i>Quick Tips
                            </button>
                            <button onclick="goTo('guide')" class="px-3 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded text-xs font-semibold transition-colors">
                                <i class="fa-solid fa-book-medical mr-1"></i>Full Guide
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    conditionsHTML += `
        </div>
        <div class="mt-6 p-4 rounded-lg bg-zinc-800/30 border border-zinc-700">
            <p class="text-xs text-zinc-400 mb-2">
                <i class="fa-solid fa-info-circle mr-1"></i>
                <strong>Important:</strong> This information is for emergency guidance only. Always seek professional medical care for serious injuries.
            </p>
            <div class="flex gap-2">
                <button onclick="clearBodyMapTooltips()" class="px-3 py-1 bg-zinc-600 hover:bg-zinc-500 text-white rounded text-xs font-semibold transition-colors">
                    <i class="fa-solid fa-times mr-1"></i>Clear Selection
                </button>
                <button onclick="goTo('guide')" class="px-3 py-1 bg-blue-600 hover:bg-blue-500 text-white rounded text-xs font-semibold transition-colors">
                    <i class="fa-solid fa-book-medical mr-1"></i>View All Guides
                </button>
            </div>
        </div>
    `;
    
    zonePanel.innerHTML = conditionsHTML;
}

function showConditionTooltip(zone, conditionName, stepsText) {
    console.log('showConditionTooltip called with:', zone, conditionName, stepsText);
    try {
        const tooltipText = conditionName + ': ' + stepsText;
        showBodyMapTooltip(zone, tooltipText);
        console.log('Tooltip displayed successfully');
    } catch (error) {
        console.error('Error in showConditionTooltip:', error);
    }
}

function clearBodyMapTooltips() {
    // Remove all tooltips
    const tooltips = document.querySelectorAll('.body-map-tooltip');
    tooltips.forEach(tooltip => tooltip.remove());
    
    // Reset zone panel
    const zonePanel = document.getElementById('zonePanel');
    if (zonePanel) {
        zonePanel.innerHTML = `
            <div class="card-s p-8 text-center h-full flex flex-col items-center justify-center">
                <i class="fa-solid fa-hand-pointer text-4xl text-zinc-600 mb-4"></i>
                <p class="text-zinc-400 text-lg">Select a body region to view related conditions</p>
            </div>
        `;
    }
}

function clearZonePanel() {
    const zonePanel = document.getElementById('zonePanel');
    if (zonePanel) {
        zonePanel.innerHTML = `
            <div class="card-s p-8 text-center h-full flex flex-col items-center justify-center">
                <i class="fa-solid fa-hand-pointer text-4xl text-zinc-600 mb-4"></i>
                <p class="text-zinc-400 text-lg">Select a body region to view related conditions</p>
            </div>
        `;
    }
}

function closeZoneConditions() {
    const overlay = document.querySelector('.zone-conditions-overlay');
    if (overlay) {
        overlay.remove();
    }
}

function getZoneDisplayName(zone) {
    const displayNames = {
        'body-wide': 'Body-Wide Conditions',
        'head': 'Head & Face',
        'chest': 'Chest',
        'abdomen': 'Abdomen',
        'left-arm': 'Left Arm',
        'right-arm': 'Right Arm',
        'left-leg': 'Left Leg',
        'right-leg': 'Right Leg',
        'left-hand': 'Left Hand',
        'right-hand': 'Right Hand',
        'left-foot': 'Left Foot',
        'right-foot': 'Right Foot'
    };
    return displayNames[zone] || zone;
}

function callEmergency() {
    console.log('callEmergency function called');
    // Find first emergency contact from database
    const emergencyContacts = @json($emergencyContacts);
    const emergencyContact = emergencyContacts.find(c => c.type === 'emergency');
    
    if (emergencyContact) {
        if (confirm(`Call ${emergencyContact.name} (${emergencyContact.phone})?`)) {
            window.location.href = 'tel:' + emergencyContact.phone;
        }
    } else {
        alert('No emergency contact found in database');
    }
}

function openSOS() {
    console.log('openSOS function called');
    const sosOverlay = document.getElementById('sosOverlay');
    if (sosOverlay) {
        sosOverlay.classList.add('open');
    }
}

// CPR Assistant Functions
var cprInterval, cprCount = 0, cprBPM = 110, cprPhase = 'Ready', cprCompressions = 0;

function toggleCPR() {
    console.log('toggleCPR called, current phase:', cprPhase);
    
    if (cprInterval) {
        // Stop CPR
        clearInterval(cprInterval);
        cprInterval = null;
        const cprBtn = document.getElementById('cprBtn');
        if (cprBtn) {
            cprBtn.innerHTML = '<i class="fa-solid fa-play mr-2"></i>Start';
        }
        cprPhase = 'Ready';
    } else {
        // Start CPR
        cprInterval = setInterval(function() {
            if (cprCompressions < 30) {
                // Compression phase
                cprCompressions++;
                cprCount++;
                
                const cprCountEl = document.getElementById('cprCount');
                if (cprCountEl) cprCountEl.textContent = cprCount;
                
                const cprPulse = document.getElementById('cprPulse');
                if (cprPulse) {
                    cprPulse.style.transform = 'scale(1.1)';
                    setTimeout(function() {
                        cprPulse.style.transform = 'scale(1)';
                    }, 100);
                }
            } else {
                // Switch to breaths
                cprPhase = 'Breaths';
                clearInterval(cprInterval);
                cprInterval = setInterval(function() {
                    cprCompressions++;
                    if (cprCompressions >= 32) {
                        cprCompressions = 0;
                        cprPhase = 'Compressions';
                        clearInterval(cprInterval);
                        toggleCPR();
                        toggleCPR();
                    }
                }, 2000);
            }
        }, 60000 / cprBPM);
        
        const cprBtn = document.getElementById('cprBtn');
        if (cprBtn) {
            cprBtn.innerHTML = '<i class="fa-solid fa-pause mr-2"></i>Pause';
        }
        cprPhase = 'Compressions';
    }
    
    const cprPhaseEl = document.getElementById('cprPhase');
    if (cprPhaseEl) cprPhaseEl.textContent = cprPhase;
}

function adjBPM(delta) {
    console.log('adjBPM called with delta:', delta);
    cprBPM = Math.max(80, Math.min(140, cprBPM + delta));
    
    const cprBPMEl = document.getElementById('cprBPM');
    if (cprBPMEl) cprBPMEl.textContent = cprBPM;
    
    if (cprInterval) {
        clearInterval(cprInterval);
        cprInterval = null;
        toggleCPR();
        toggleCPR();
    }
}

function resetCPR() {
    console.log('resetCPR called');
    clearInterval(cprInterval);
    cprInterval = null;
    cprCount = 0;
    cprCompressions = 0;
    cprPhase = 'Ready';
    
    const cprCountEl = document.getElementById('cprCount');
    if (cprCountEl) cprCountEl.textContent = '0';
    
    const cprPhaseEl = document.getElementById('cprPhase');
    if (cprPhaseEl) cprPhaseEl.textContent = 'Ready';
    
    const cprBtn = document.getElementById('cprBtn');
    if (cprBtn) {
        cprBtn.innerHTML = '<i class="fa-solid fa-play mr-2"></i>Start';
    }
}

// Show checker result function
function showResult(severity, condition, action) {
    console.log('showResult called with:', severity, condition, action);
    const html = `
        <div class="flex items-center gap-2 mb-6">
            <div class="step-dot"></div>
            <div class="h-px flex-1 bg-zinc-800"></div>
            <div class="step-dot"></div>
            <div class="h-px flex-1 bg-zinc-800"></div>
            <div class="step-dot active"></div>
        </div>
        <div class="card-s p-6 mb-4">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-lg">${condition}</h3>
                    <span class="sev-${severity} text-xs px-3 py-1 rounded-full font-semibold uppercase mt-2 inline-block">${severity}</span>
                </div>
            </div>
            <p class="text-zinc-300 text-sm mb-6">${action}</p>
            <div class="flex gap-3">
                <button onclick="resetChecker()" class="px-6 py-3 bg-zinc-800 hover:bg-zinc-700 rounded-xl font-semibold">
                    <i class="fa-solid fa-arrow-left mr-2"></i>Start Over
                </button>
                <!-- Emergency button removed for critical conditions -->
            </div>
        </div>
    `;
    document.getElementById('pg-checker').innerHTML = html;
}

// Reset checker function
function resetChecker() {
    console.log('resetChecker called');
    if (typeof window.checkerQ1 !== 'undefined' && window.checkerQ1) {
        document.getElementById('pg-checker').innerHTML = window.checkerQ1;
        console.log('resetChecker completed using stored checkerQ1');
    } else {
        console.error('checkerQ1 is not defined - using hardcoded fallback');
        // Fallback: create the initial checker HTML structure
        const fallbackHTML = `
            <div class="mb-6"><h2 class="text-3xl font-bold mb-1">Smart Symptom Checker</h2><p class="text-zinc-400">Answer a few questions to get a triage assessment</p></div>
            <div class="max-w-2xl">
                <div class="flex items-center gap-2 mb-6">
                    <div class="step-dot active"></div>
                    <div class="h-px flex-1 bg-zinc-800"></div>
                    <div class="step-dot"></div>
                    <div class="h-px flex-1 bg-zinc-800"></div>
                    <div class="step-dot"></div>
                </div>
                <div class="card-s p-6 mb-4">
                    <p class="text-xs text-zinc-500 mb-1">Question 1</p>
                    <h3 class="font-semibold text-lg">What is the main problem?</h3>
                </div>
                <div class="space-y-3">
                    <button onclick="checkerNext('chest')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30">
                        <div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-heart text-zinc-400"></i>
                        </div>
                        <span class="text-sm font-medium">Chest pain or discomfort</span>
                        <i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i>
                    </button>
                    <button onclick="checkerNext('breathing')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30">
                        <div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-lungs text-zinc-400"></i>
                        </div>
                        <span class="text-sm font-medium">Difficulty breathing</span>
                        <i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i>
                    </button>
                    <button onclick="checkerNext('bleeding')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30">
                        <div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-droplet text-zinc-400"></i>
                        </div>
                        <span class="text-sm font-medium">Bleeding or wound</span>
                        <i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i>
                    </button>
                    <button onclick="checkerNext('unconscious')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30">
                        <div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-person-falling text-zinc-400"></i>
                        </div>
                        <span class="text-sm font-medium">Person is unconscious</span>
                        <i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i>
                    </button>
                    <button onclick="checkerNext('burn')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30">
                        <div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-fire text-zinc-400"></i>
                        </div>
                        <span class="text-sm font-medium">Burn or scald</span>
                        <i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i>
                    </button>
                    <button onclick="checkerNext('seizure')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30">
                        <div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-bolt text-zinc-400"></i>
                        </div>
                        <span class="text-sm font-medium">Seizure</span>
                        <i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i>
                    </button>
                    <button onclick="checkerNext('fracture')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30">
                        <div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-bone text-zinc-400"></i>
                        </div>
                        <span class="text-sm font-medium">Suspected broken bone</span>
                        <i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i>
                    </button>
                    <button onclick="checkerNext('allergic')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30">
                        <div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-syringe text-zinc-400"></i>
                        </div>
                        <span class="text-sm font-medium">Allergic reaction</span>
                        <i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i>
                    </button>
                </div>
            </div>
        `;
        
        const checkerElement = document.getElementById('pg-checker');
        if (checkerElement) {
            checkerElement.innerHTML = fallbackHTML;
            // Store this as the new checkerQ1
            window.checkerQ1 = fallbackHTML;
            console.log('resetChecker completed using hardcoded fallback');
        } else {
            console.error('pg-checker element not found');
        }
    }
}

// Symptom checker function
function checkerNext(type) {
    console.log('checkerNext called with type:', type);
    
    const questions = {
        chest: {
            question: 'Sudden or gradual?',
            options: [
                {
                    text: 'Sudden, crushing pain',
                    result: { severity: 'critical', condition: 'Heart Attack', action: 'Call 912 IMMEDIATELY. Give aspirin. Keep still.' }
                },
                {
                    text: 'Gradual, mild',
                    result: { severity: 'urgent', condition: 'Angina', action: 'Rest. Help with nitroglycerin if prescribed. Call 912 if persists.' }
                },
                {
                    text: 'Sharp pain when breathing',
                    result: { severity: 'moderate', condition: 'Rib Fracture', action: 'Immobilize. Support chest when coughing. Seek medical attention.' }
                }
            ]
        },
        breathing: {
            question: 'Can they speak or clutching throat?',
            options: [
                {
                    text: 'Cannot speak, clutching throat',
                    result: { severity: 'critical', condition: 'Choking', action: '5 back blows + 5 abdominal thrusts. Alternate until clear. If unconscious, begin CPR. Call 912.' }
                },
                {
                    text: 'Wheezing, known asthma',
                    result: { severity: 'urgent', condition: 'Asthma Attack', action: 'Use rescue inhaler. Keep upright. Call 912 if no improvement.' }
                },
                {
                    text: 'Gradual shortness of breath',
                    result: { severity: 'urgent', condition: 'Difficulty Breathing', action: 'Sit upright. Loosen clothing. Call 912. Be prepared for CPR.' }
                }
            ]
        },
        bleeding: {
            question: 'How would you describe it?',
            options: [
                {
                    text: 'Heavy, spurting',
                    result: { severity: 'critical', condition: 'Severe Bleeding', action: 'Apply pressure. Call 912. Use tourniquet if life-threatening. Note time.' }
                },
                {
                    text: 'Moderate cut',
                    result: { severity: 'moderate', condition: 'Moderate Bleeding', action: 'Pressure 10 min. Elevate. Clean and bandage.' }
                },
                {
                    text: 'Minor scrape',
                    result: { severity: 'minor', condition: 'Minor Wound', action: 'Clean with water. Apply antiseptic. Cover with bandage.' }
                }
            ]
        },
        unconscious: {
            question: 'Is the person breathing?',
            options: [
                {
                    text: 'Not breathing or gasping',
                    result: { severity: 'critical', condition: 'Cardiac Arrest', action: 'Call 912. Begin CPR: 30 compressions to 2 breaths.' }
                },
                {
                    text: 'Breathing normally',
                    result: { severity: 'urgent', condition: 'Unconscious but Breathing', action: 'Call 912. Recovery position. Monitor breathing.' }
                },
                {
                    text: 'Not sure',
                    result: { severity: 'critical', condition: 'Check Breathing', action: 'Look at chest 10 seconds. Not breathing = CPR. Breathing = recovery position. Call 912.' }
                }
            ]
        },
        burn: {
            question: 'How large?',
            options: [
                {
                    text: 'Larger than palm or face/hands',
                    result: { severity: 'critical', condition: 'Major Burn', action: 'Call 912. Cool water 20 min. Do NOT remove stuck clothing.' }
                },
                {
                    text: 'Smaller than palm',
                    result: { severity: 'moderate', condition: 'Minor Burn', action: 'Cool water 20 min. Cover with cling film.' }
                }
            ]
        },
        seizure: {
            question: 'Currently seizing?',
            options: [
                {
                    text: 'Currently seizing',
                    result: { severity: 'urgent', condition: 'Active Seizure', action: 'Clear hazards. Do NOT restrain. Protect head. Time it. Call 912 if over 5 min.' }
                },
                {
                    text: 'Seizure stopped',
                    result: { severity: 'moderate', condition: 'Post-Seizure', action: 'Recovery position. Reassure. Call 912 if first seizure.' }
                }
            ]
        },
        fracture: {
            question: 'Bone visible or deformed?',
            options: [
                {
                    text: 'Bone visible or severe deformity',
                    result: { severity: 'critical', condition: 'Open Fracture', action: 'Call 912. Do NOT push bone back. Cover wound.' }
                },
                {
                    text: 'Swollen, no visible bone',
                    result: { severity: 'urgent', condition: 'Closed Fracture', action: 'Immobilize with splint. Ice wrapped in cloth. Elevate. Seek medical help.' }
                },
                {
                    text: 'Can bear weight',
                    result: { severity: 'moderate', condition: 'Possible Sprain', action: 'RICE: Rest, Ice, Compression, Elevation. Seek eval if not better after 24h.' }
                }
            ]
        },
        allergic: {
            question: 'Severe reaction?',
            options: [
                {
                    text: 'Yes, severe symptoms',
                    result: { severity: 'critical', condition: 'Anaphylaxis', action: 'Use epinephrine NOW. Call 912. Second dose in 5 min if no improvement.' }
                },
                {
                    text: 'Mild rash, itching',
                    result: { severity: 'moderate', condition: 'Mild Allergic Reaction', action: 'Antihistamine. Cool compress. Monitor for worsening.' }
                }
            ]
        }
    };
    
    if (!questions[type]) {
        console.error('Unknown symptom type:', type);
        return;
    }
    
    const q = questions[type];
    let html = `
        <div class="flex items-center gap-2 mb-6">
            <div class="step-dot"></div>
            <div class="h-px flex-1 bg-zinc-800"></div>
            <div class="step-dot active"></div>
            <div class="h-px flex-1 bg-zinc-800"></div>
            <div class="step-dot"></div>
        </div>
        <div class="card-s p-6 mb-4">
            <p class="text-xs text-zinc-500 mb-1">Question 2</p>
            <h3 class="font-semibold text-lg">${q.question}</h3>
        </div>
        <div class="space-y-3">
    `;
    
    q.options.forEach(option => {
        html += `
            <button onclick="showResult('${option.result.severity}', '${option.result.condition}', '${option.result.action}')" 
                    class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30">
                <div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-question text-zinc-400"></i>
                </div>
                <span class="text-sm font-medium">${option.text}</span>
                <i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i>
            </button>
        `;
    });
    
    html += `
        </div>
        <button onclick="resetChecker()" class="text-zinc-500 hover:text-zinc-300 text-sm transition-colors">
            <i class="fa-solid fa-arrow-left mr-1"></i> Start Over
        </button>
    `;
    
    document.getElementById('pg-checker').innerHTML = html;
    console.log('Checker question displayed for type:', type);
}

// Close modal or guide details
function closeModal() {
    console.log('closeModal function called');
    try {
        // Check if we're in the guide page and showing details
        const guidePage = document.getElementById('pg-guide');
        console.log('Guide page element:', guidePage);
        
        if (guidePage && guidePage.innerHTML.includes('Close')) {
            console.log('Detected guide detail view, restoring guide list');
            // Show toast message before closing
            if (typeof toast === 'function') {
                toast('Returning to first aid guide list');
            }
            // Restore the original guide content by going back to the guide page
            setTimeout(() => {
                goTo('guide');
            }, 500);
        } else {
            console.log('Trying to close modal');
            // Try to close modal if it exists
            const modalBg = document.getElementById('modalBg');
            if (modalBg) {
                modalBg.classList.remove('open');
                console.log('Modal closed');
                if (typeof toast === 'function') {
                    toast('Modal closed');
                }
            } else {
                console.log('No modal found to close');
            }
        }
    } catch (error) {
        console.error('Error in closeModal:', error);
    }
}

// Show guide details from database
function showGuideDetails(guideId) {
    console.log('showGuideDetails called with ID:', guideId);
    fetch('/first-aid-guide/' + guideId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const guide = data.guide;
                const symptoms = JSON.parse(guide.symptoms || '[]');
                const steps = JSON.parse(guide.steps || '[]');
                const doTips = JSON.parse(guide.do_tips || '[]');
                const dontTips = JSON.parse(guide.dont_tips || '[]');
                const requiredItems = JSON.parse(guide.required_items || '[]');
                
                let html = `
                    <div class="flex items-center justify-between p-5 border-b border-zinc-800">
                        <h3 class="font-semibold text-lg">${guide.title}</h3>
                        <button onclick="closeModal()" class="w-8 h-8 rounded-lg hover:bg-zinc-800 flex items-center justify-center">
                            <i class="fa-solid fa-xmark text-zinc-400"></i>
                        </button>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="sev-${guide.severity} text-xs px-3 py-1 rounded-full font-semibold uppercase">${guide.severity}</span>
                            <span class="text-xs text-zinc-500 bg-zinc-800 px-2 py-1 rounded-full">${guide.category}</span>
                            ${guide.region !== 'global' ? `<span class="text-xs text-blue-400 bg-blue-600/20 px-2 py-1 rounded-full">${guide.region}</span>` : ''}
                        </div>
                        
                        <p class="text-zinc-300 text-sm mb-6">${guide.description}</p>
                        
                        ${symptoms.length > 0 ? `
                        <div class="mb-6">
                            <h4 class="font-semibold text-sm text-white mb-3">Symptoms:</h4>
                            <ul class="space-y-2">
                                ${symptoms.map(symptom => `<li class="text-sm text-zinc-300 flex items-start gap-2"><i class="fa-solid fa-circle text-xs text-red-400 mt-1.5"></i><span>${symptom}</span></li>`).join('')}
                            </ul>
                        </div>
                        ` : ''}
                        
                        <div class="mb-6">
                            <h4 class="font-semibold text-sm text-white mb-3">Steps to Follow:</h4>
                            <ol class="space-y-3">
                                ${steps.map((step, index) => `<li class="text-sm text-zinc-300 flex gap-3"><span class="w-6 h-6 rounded-full bg-red-600/20 text-red-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">${index + 1}</span><span>${step}</span></li>`).join('')}
                            </ol>
                        </div>
                        
                        ${doTips.length > 0 ? `
                        <div class="mb-6">
                            <h4 class="font-semibold text-sm text-white mb-3 text-green-400">Do:</h4>
                            <ul class="space-y-2">
                                ${doTips.map(tip => `<li class="text-sm text-zinc-300 flex items-start gap-2"><i class="fa-solid fa-check text-xs text-green-400 mt-1.5"></i><span>${tip}</span></li>`).join('')}
                            </ul>
                        </div>
                        ` : ''}
                        
                        ${dontTips.length > 0 ? `
                        <div class="mb-6">
                            <h4 class="font-semibold text-sm text-white mb-3 text-red-400">Don't:</h4>
                            <ul class="space-y-2">
                                ${dontTips.map(tip => `<li class="text-sm text-zinc-300 flex items-start gap-2"><i class="fa-solid fa-times text-xs text-red-400 mt-1.5"></i><span>${tip}</span></li>`).join('')}
                            </ul>
                        </div>
                        ` : ''}
                        
                        ${requiredItems.length > 0 ? `
                        <div class="mb-6">
                            <h4 class="font-semibold text-sm text-white mb-3">Required Items:</h4>
                            <div class="flex flex-wrap gap-2">
                                ${requiredItems.map(item => `<span class="text-xs bg-zinc-800 text-zinc-300 px-2 py-1 rounded">${item}</span>`).join('')}
                            </div>
                        </div>
                        ` : ''}
                        
                        <div class="flex gap-3">
                            <button onclick="closeModal()" class="px-6 py-3 bg-zinc-800 hover:bg-zinc-700 rounded-xl font-semibold">
                                <i class="fa-solid fa-xmark mr-2"></i>Close
                            </button>
                            <!-- Emergency call button removed for critical conditions -->
                        </div>
                    </div>
                `;
                
                document.getElementById('pg-guide').innerHTML = html;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

window.goTo = goTo;
window.showZone = showZone;
window.showZoneFallback = showZoneFallback;
window.closeZoneConditions = closeZoneConditions;
window.displayZoneConditions = displayZoneConditions;
window.clearZonePanel = clearZonePanel;
window.clearBodyMapTooltips = clearBodyMapTooltips;
window.showBodyMapTooltip = showBodyMapTooltip;
window.showConditionTooltip = showConditionTooltip;
window.getTooltipPosition = getTooltipPosition;
window.updateZonePanel = updateZonePanel;
window.getZoneDisplayName = getZoneDisplayName;
window.callEmergency = callEmergency;
window.showGuideDetails = showGuideDetails;
window.closeModal = closeModal;
window.checkerNext = checkerNext;
window.showResult = showResult;
window.resetChecker = resetChecker;
window.openSOS = openSOS;
window.toggleCPR = toggleCPR;
window.adjBPM = adjBPM;
window.resetCPR = resetCPR;
console.log('goTo function loaded inline:', typeof goTo);
console.log('showZone function loaded inline:', typeof showZone);
console.log('closeZoneConditions function loaded inline:', typeof closeZoneConditions);
console.log('displayZoneConditions function loaded inline:', typeof displayZoneConditions);
console.log('getZoneDisplayName function loaded inline:', typeof getZoneDisplayName);
console.log('callEmergency function loaded inline:', typeof callEmergency);
console.log('showGuideDetails function loaded inline:', typeof showGuideDetails);
console.log('closeModal function loaded inline:', typeof closeModal);
</script>

<!-- SOS OVERLAY -->
<div id="sosOverlay" class="sos-overlay sos-pulse-border flex-col items-center justify-center text-center p-6">
<div class="mb-6"><div class="w-24 h-24 rounded-full bg-red-600/30 flex items-center justify-center mx-auto mb-4" style="animation:pulseSlow 1s ease-in-out infinite"><i class="fa-solid fa-triangle-exclamation text-5xl text-red-300"></i></div>
<h2 class="text-4xl font-bold text-red-100" style="text-shadow:0 0 20px rgba(239,68,68,.3)">SOS EMERGENCY</h2>
<p class="text-red-200/70 mt-2 text-lg">Stay calm. Help is on the way.</p></div>
<div class="flex flex-col gap-3 w-full max-w-xs mb-8">
<a href="tel:912" class="flex items-center justify-center gap-3 bg-red-600 hover:bg-red-500 text-white py-4 px-6 rounded-xl text-lg font-semibold transition-all"><i class="fa-solid fa-phone"></i> Call Emergency (912)</a>
<button onclick="closeSOS()" class="flex items-center justify-center gap-3 bg-white/10 hover:bg-white/15 text-white py-4 px-6 rounded-xl text-lg font-semibold transition-all"><i class="fa-solid fa-location-dot"></i> Share Location</button></div>
<div class="grid grid-cols-2 gap-3 w-full max-w-sm mb-6">
<button onclick="closeSOS();goTo('cpr')" class="card p-4 text-center hover:border-red-500/50 cursor-pointer"><i class="fa-solid fa-heart-pulse text-2xl text-red-400 mb-1"></i><p class="text-sm text-zinc-300">CPR</p></button>
<button onclick="closeSOS();goTo('guide')" class="card p-4 text-center hover:border-red-500/50 cursor-pointer"><i class="fa-solid fa-droplet text-2xl text-red-400 mb-1"></i><p class="text-sm text-zinc-300">Bleeding</p></button>
<button onclick="closeSOS();goTo('guide')" class="card p-4 text-center hover:border-red-500/50 cursor-pointer"><i class="fa-solid fa-lungs text-2xl text-red-400 mb-1"></i><p class="text-sm text-zinc-300">Choking</p></button>
<button onclick="closeSOS();goTo('guide')" class="card p-4 text-center hover:border-red-500/50 cursor-pointer"><i class="fa-solid fa-person-falling text-2xl text-red-400 mb-1"></i><p class="text-sm text-zinc-300">Unconscious</p></button></div>
<button onclick="closeSOS()" class="text-zinc-400 hover:text-white text-sm"><i class="fa-solid fa-xmark mr-1"></i> Exit Emergency Mode</button>
</div>

<!-- MODAL -->
<div id="modalBg" class="modal-bg" onclick="if(event.target===this)closeModal()"><div class="modal-box" id="modalBox"></div></div>

<!-- TOAST -->
<div id="toastWrap"></div>


<!-- LAYOUT -->
<div class="flex h-full">
<aside class="sidebar bg-black/50 border-r border-zinc-800/50 flex flex-col py-4 flex-shrink-0">
<div class="px-5 mt-auto">
      <div class="flex items-center gap-3 px-2">
        <div class="w-8 h-8 rounded-full bg-red-600/20 flex items-center justify-center">
          <i class="fa-solid fa-user-shield text-red-400 text-xs"></i>
        </div>
        <div>
          <p class="text-xs font-medium">{{ Auth::user()->name }}</p>
          @if(Auth::user()->is_admin)
          <p class="text-[11px] text-zinc-500">Administrator</p>
          @else
          <p class="text-[11px] text-zinc-500">User</p>
          @endif
        </div>
        @if(Auth::user()->is_admin)
          <div class="mt-3 px-2">
            <a href="/admin" class="flex items-center gap-2 px-3 py-2 bg-zinc-800 hover:bg-zinc-700 rounded-lg text-zinc-100 transition-colors">
              <i class="fa-solid fa-gauge-high w-5 text-center"></i>
              <span>Admin Panel</span>
            </a>
          </div>
        @endif
      </div>
</div>
<nav class="flex-1 flex flex-col gap-1 px-3" id="sideNav">
<button class="nav-item active flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm w-full text-left" onclick="goTo('dashboard')"><i class="fa-solid fa-house-medical w-5 text-center"></i><span>Dashboard</span></button>
<button class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm w-full text-left text-zinc-400" onclick="goTo('bodymap')"><i class="fa-solid fa-person w-5 text-center"></i><span>Body Map</span></button>
<button class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm w-full text-left text-zinc-400" onclick="goTo('checker')"><i class="fa-solid fa-stethoscope w-5 text-center"></i><span>Symptom Checker</span></button>
<button class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm w-full text-left text-zinc-400" onclick="goTo('guide')"><i class="fa-solid fa-book-medical w-5 text-center"></i><span>First Aid Guide</span></button>
<button class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm w-full text-left text-zinc-400" onclick="goTo('cpr')"><i class="fa-solid fa-heart-circle-check w-5 text-center"></i><span>CPR Assistant</span></button>
<button class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm w-full text-left text-zinc-400" onclick="goTo('contacts')"><i class="fa-solid fa-phone-volume w-5 text-center"></i><span>Contacts</span></button>
<div class="px-5 mt-auto">
<div class="bg-[#18181B] border border-zinc-800 rounded-lg p-3">
<p class="text-[11px] text-zinc-500 mb-1">Emergency Number</p>
<a href="tel:912" class="text-red-400 font-bold text-lg hover:text-red-300 transition-colors">912</a>
</div>
<div class="px-5 mt-2">
<p class="text-[11px] text-zinc-600">Logged in as:</p>
<p class="text-[11px text-teal-400 font-medium truncate">{{ Auth::user()->name }}</p>
</div>
<form method="POST" action="/logout" class="mt-3 px-2">
@csrf
<button type="submit" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm w-full text-left text-red-400 hover:bg-red-600/10"><i class="fa-solid fa-right-from-bracket w-5 text-center"></i><span>Logout</span></button>
</form>
</div>
</aside>

<main class="flex-1 overflow-y-auto main-c p-6 md:p-8">

<!-- DASHBOARD -->
<div class="page active" id="pg-dashboard">
<div class="mb-8"><h2 class="text-3xl font-bold mb-1">Welcome, {{ Auth::user()->name }}</h2><p class="text-zinc-400">Here is your emergency toolkit overview</p></div>

<!-- Quick Emergency Search -->
<div class="mb-8">
  <div class="bg-[#18181B]/80 backdrop-blur-xl border border-zinc-800 rounded-2xl p-6">
    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center gap-3">
        <i class="fa-solid fa-search text-red-400 text-lg"></i>
        <h3 class="text-lg font-semibold">Emergency Search</h3>
              </div>
      
    </div>
    <div class="relative">
      <input 
        type="text" 
        id="emergencySearch" 
        placeholder="Describe symptoms: 'chest pain', 'bleeding', 'choking'..."
        class="search-input text-lg pr-12"
        autocomplete="off"
      >
      <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500"></i>
      <button onclick="performSearchClick()" class="absolute right-2 top-1/2 -translate-y-1/2 bg-red-600 hover:bg-red-500 text-white px-3 py-1 rounded-lg text-sm font-medium transition-colors">
        Search
      </button>
      <div id="searchResults" class="absolute top-full left-0 right-0 mt-2 bg-[#18181B]/95 backdrop-blur-xl border border-zinc-800 rounded-xl shadow-2xl hidden z-50 max-h-96 overflow-y-auto">
      </div>
    </div>
    
        </div>
    <div class="flex items-center justify-between mt-3">
      <p id="searchModeInfo" class="text-zinc-500 text-xs">
        <i class="fa-solid fa-brain text-green-400 mr-1"></i>
        AI-powered emergency recommendations with fallback guides
      </p>
    </div>
  </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
<div class="card-s p-4"><p class="text-zinc-500 text-xs mb-1">Conditions</p><p class="text-2xl font-bold" style="color:#2DD4BF">12</p></div>
<div class="card-s p-4"><p class="text-zinc-500 text-xs mb-1">Body Zones</p><p class="text-2xl font-bold" style="color:#2DD4BF">7</p></div>
<div class="card-s p-4"><p class="text-zinc-500 text-xs mb-1">Kit Items</p><p class="text-2xl font-bold" style="color:#2DD4BF">25</p></div>
<div class="card-s p-4"><p class="text-zinc-500 text-xs mb-1">Role</p><p class="text-2xl font-bold" style="color:#F97316">User</p></div>
</div>
<h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
<div class="card quick-card p-4" onclick="goTo('guide')"><div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:rgba(239,68,68,.12)"><i class="fa-solid fa-heart-crack" style="color:#EF4444"></i></div><p class="text-sm font-semibold">Cardiac Arrest</p></div>
<div class="card quick-card p-4" onclick="goTo('guide')"><div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:rgba(239,68,68,.12)"><i class="fa-solid fa-heart" style="color:#EF4444"></i></div><p class="text-sm font-semibold">Heart Attack</p></div>
<div class="card quick-card p-4" onclick="goTo('guide')"><div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:rgba(249,115,22,.12)"><i class="fa-solid fa-lungs" style="color:#F97316"></i></div><p class="text-sm font-semibold">Choking</p></div>
<div class="card quick-card p-4" onclick="goTo('guide')"><div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:rgba(239,68,68,.12)"><i class="fa-solid fa-droplet" style="color:#EF4444"></i></div><p class="text-sm font-semibold">Severe Bleeding</p></div>
<div class="card quick-card p-4" onclick="goTo('guide')"><div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:rgba(239,68,68,.12)"><i class="fa-solid fa-brain" style="color:#EF4444"></i></div><p class="text-sm font-semibold">Stroke</p></div>
<div class="card quick-card p-4" onclick="goTo('guide')"><div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:rgba(239,68,68,.12)"><i class="fa-solid fa-syringe" style="color:#EF4444"></i></div><p class="text-sm font-semibold">Anaphylaxis</p></div>
<div class="card quick-card p-4" onclick="goTo('cpr')"><div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:rgba(45,212,191,.12)"><i class="fa-solid fa-heart-pulse" style="color:#2DD4BF"></i></div><p class="text-sm font-semibold">Start CPR</p></div>
<div class="card quick-card p-4" onclick="goTo('bodymap')"><div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:rgba(45,212,191,.12)"><i class="fa-solid fa-person" style="color:#2DD4BF"></i></div><p class="text-sm font-semibold">Body Map</p></div>
</div>
<h3 class="text-lg font-semibold mb-4">DRABC Protocol</h3>
<div class="grid grid-cols-2 md:grid-cols-5 gap-3">
<div class="card-s p-4 text-center"><div class="w-10 h-10 rounded-full bg-red-600/20 flex items-center justify-center mx-auto mb-2 text-red-400 font-bold">D</div><p class="text-sm font-semibold">Danger</p><p class="text-xs text-zinc-500 mt-1">Check hazards</p></div>
<div class="card-s p-4 text-center"><div class="w-10 h-10 rounded-full bg-orange-600/20 flex items-center justify-center mx-auto mb-2 text-orange-400 font-bold">R</div><p class="text-sm font-semibold">Response</p><p class="text-xs text-zinc-500 mt-1">Check consciousness</p></div>
<div class="card-s p-4 text-center"><div class="w-10 h-10 rounded-full bg-yellow-600/20 flex items-center justify-center mx-auto mb-2 text-yellow-400 font-bold">A</div><p class="text-sm font-semibold">Airway</p><p class="text-xs text-zinc-500 mt-1">Open and clear</p></div>
<div class="card-s p-4 text-center"><div class="w-10 h-10 rounded-full bg-teal-600/20 flex items-center justify-center mx-auto mb-2 text-teal-400 font-bold">B</div><p class="text-sm font-semibold">Breathing</p><p class="text-xs text-zinc-500 mt-1">Look, listen, feel</p></div>
<div class="card-s p-4 text-center"><div class="w-10 h-10 rounded-full bg-green-600/20 flex items-center justify-center mx-auto mb-2 text-green-400 font-bold">C</div><p class="text-sm font-semibold">Circulation</p><p class="text-xs text-zinc-500 mt-1">Pulse, bleeding</p></div>
</div>
</div>

<!-- BODY MAP -->
<div class="page" id="pg-bodymap">
<div class="mb-6"><h2 class="text-3xl font-bold mb-1">Interactive Body Map</h2><p class="text-zinc-400">Click on a body region to see related conditions</p></div>
<div class="mb-4">
    <button onclick="showZone('body-wide')" class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg font-semibold transition-colors">
        <i class="fa-solid fa-exclamation-triangle mr-2"></i>
        View Body-Wide Emergency Conditions
    </button>
</div>
<div class="flex flex-col lg:flex-row gap-6">
<div class="card-s p-6 flex-shrink-0 flex items-center justify-center" style="min-height:440px">
<svg viewBox="0 0 200 440" width="220" height="480">
<g class="body-outline"><ellipse cx="100" cy="42" rx="24" ry="30"/><rect x="92" y="72" width="16" height="16" rx="5"/><path d="M62,88 Q60,88 59,90 L56,192 Q55,196 60,196 L140,196 Q145,196 144,192 L141,90 Q140,88 138,88 Z"/><path d="M59,92 L38,100 L24,172 L36,175 L48,112 L59,106 Z"/><path d="M141,92 L162,100 L176,172 L164,175 L152,112 L141,106 Z"/><path d="M60,196 L52,300 L44,388 L56,390 L66,305 L86,305 L86,196 Z"/><path d="M140,196 L148,300 L156,388 L144,390 L134,305 L114,305 L114,196 Z"/></g>
<ellipse cx="100" cy="42" rx="30" ry="36" class="body-zone" onclick="showZone('head')"/>
<rect x="57" y="86" width="86" height="52" rx="6" class="body-zone" onclick="showZone('chest')"/>
<rect x="57" y="140" width="86" height="56" rx="6" class="body-zone" onclick="showZone('abdomen')"/>
<rect x="22" y="90" width="40" height="88" rx="12" class="body-zone" onclick="showZone('left-arm')" transform="rotate(-8,42,90)"/>
<rect x="138" y="90" width="40" height="88" rx="12" class="body-zone" onclick="showZone('right-arm')" transform="rotate(8,158,90)"/>
<rect x="40" y="196" width="50" height="200" rx="12" class="body-zone" onclick="showZone('left-leg')" transform="rotate(2,65,196)"/>
<rect x="110" y="196" width="50" height="200" rx="12" class="body-zone" onclick="showZone('right-leg')" transform="rotate(-2,135,196)"/>
<!-- Hands -->
<ellipse cx="28" cy="178" rx="12" ry="18" class="body-zone" onclick="showZone('left-hand')" transform="rotate(-15,28,178)"/>
<ellipse cx="172" cy="178" rx="12" ry="18" class="body-zone" onclick="showZone('right-hand')" transform="rotate(15,172,178)"/>
<!-- Feet -->
<ellipse cx="65" cy="390" rx="15" ry="20" class="body-zone" onclick="showZone('left-foot')"/>
<ellipse cx="135" cy="390" rx="15" ry="20" class="body-zone" onclick="showZone('right-foot')"/>
</svg>
</div>
<div class="flex-1" id="zonePanel"><div class="card-s p-8 text-center h-full flex flex-col items-center justify-center"><i class="fa-solid fa-hand-pointer text-4xl text-zinc-600 mb-4"></i><p class="text-zinc-400 text-lg">Select a body region to view related conditions</p></div></div>
</div>
</div>

<!-- SYMPTOM CHECKER -->
<div class="page" id="pg-checker">
<div class="mb-6"><h2 class="text-3xl font-bold mb-1">Smart Symptom Checker</h2><p class="text-zinc-400">Answer a few questions to get a triage assessment</p></div>
<div class="max-w-2xl">
<div class="flex items-center gap-2 mb-6"><div class="step-dot active"></div><div class="h-px flex-1 bg-zinc-800"></div><div class="step-dot"></div><div class="h-px flex-1 bg-zinc-800"></div><div class="step-dot"></div></div>
<div class="card-s p-6 mb-4"><p class="text-xs text-zinc-500 mb-1">Question 1</p><h3 class="font-semibold text-lg">What is the main problem?</h3></div>
<div class="space-y-3">
<button onclick="checkerNext('chest')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30"><div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-heart text-zinc-400"></i></div><span class="text-sm font-medium">Chest pain or discomfort</span><i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i></button>
<button onclick="checkerNext('breathing')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30"><div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-lungs text-zinc-400"></i></div><span class="text-sm font-medium">Difficulty breathing</span><i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i></button>
<button onclick="checkerNext('bleeding')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30"><div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-droplet text-zinc-400"></i></div><span class="text-sm font-medium">Bleeding or wound</span><i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i></button>
<button onclick="checkerNext('unconscious')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30"><div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-person-falling text-zinc-400"></i></div><span class="text-sm font-medium">Person is unconscious</span><i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i></button>
<button onclick="checkerNext('burn')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30"><div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-fire text-zinc-400"></i></div><span class="text-sm font-medium">Burn or scald</span><i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i></button>
<button onclick="checkerNext('seizure')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30"><div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-bolt text-zinc-400"></i></div><span class="text-sm font-medium">Seizure</span><i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i></button>
<button onclick="checkerNext('fracture')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30"><div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-bone text-zinc-400"></i></div><span class="text-sm font-medium">Suspected broken bone</span><i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i></button>
<button onclick="checkerNext('allergic')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30"><div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-syringe text-zinc-400"></i></div><span class="text-sm font-medium">Allergic reaction</span><i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i></button>
</div>
</div>
</div>

<!-- FIRST AID GUIDE -->
<div class="page" id="pg-guide">
<div class="mb-6"><h2 class="text-3xl font-bold mb-1">First Aid Guide</h2><p class="text-zinc-400">Step-by-step emergency procedures</p></div>
<div id="guideContainer">
    @if($firstAidGuides->count() > 0)
    <div class="space-y-4">
        @foreach($firstAidGuides as $guide)
        <div class="card-s p-6 cursor-pointer hover:border-zinc-700 transition-all" onclick="showGuideDetails({{ $guide->id }})">
            <div class="flex items-start justify-between">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-lg bg-red-600/20 flex items-center justify-center">
                        <i class="fa-solid {{ $guide->icon ?? 'fa-book-medical' }} text-red-400 text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-lg text-white">{{ $guide->title }}</h3>
                        <p class="text-zinc-400 text-sm mb-2">{{ $guide->description }}</p>
                        <div class="flex items-center gap-3">
                            <span class="sev-{{ $guide->severity }} text-xs px-3 py-1 rounded-full font-semibold uppercase">
                                {{ $guide->severity }}
                            </span>
                            <span class="text-xs text-zinc-500 bg-zinc-800 px-2 py-1 rounded-full">
                                {{ $guide->category }}
                            </span>
                            @if($guide->region !== 'global')
                            <span class="text-xs text-blue-400 bg-blue-600/20 px-2 py-1 rounded-full">
                                {{ $guide->region }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-12">
        <i class="fa-solid fa-book-medical text-4xl text-zinc-600 mb-4"></i>
        <p class="text-zinc-400">No first aid guides available</p>
    </div>
    @endif
</div>
</div>

<!-- CPR ASSISTANT -->
<div class="page" id="pg-cpr">
<div class="mb-6"><h2 class="text-3xl font-bold mb-1">CPR Assistant</h2><p class="text-zinc-400">Real-time metronome and compression counter</p></div>
<div class="flex flex-col lg:flex-row gap-6">
<div class="flex-1"><div class="card-s p-8 flex flex-col items-center">
<div class="relative w-48 h-48 mb-6"><div class="absolute inset-0 rounded-full border-4 border-zinc-800"></div><div class="absolute inset-3 rounded-full border-2 border-zinc-700/50"></div><div class="absolute inset-0 rounded-full bg-zinc-800/30 flex items-center justify-center" id="cprPulse"><div class="text-center"><p class="text-5xl font-bold" id="cprCount">0</p><p class="text-xs text-zinc-400 mt-1">compressions</p></div></div></div>
<div class="flex items-center gap-4 mb-4"><button onclick="adjBPM(-5)" class="w-10 h-10 rounded-lg bg-zinc-800 hover:bg-zinc-700 flex items-center justify-center"><i class="fa-solid fa-minus text-sm"></i></button><div class="text-center min-w-[120px]"><p class="text-3xl font-bold text-red-400" id="cprBPM">110</p><p class="text-xs text-zinc-500">BPM</p></div><button onclick="adjBPM(5)" class="w-10 h-10 rounded-lg bg-zinc-800 hover:bg-zinc-700 flex items-center justify-center"><i class="fa-solid fa-plus text-sm"></i></button></div>
<div class="flex items-center gap-2 mb-4 text-sm"><span class="px-3 py-1 rounded-full bg-zinc-800 text-zinc-300" id="cprPhase">Ready</span><span class="text-zinc-500">Ratio: 30:2</span></div>
<div class="flex gap-3"><button onclick="toggleCPR()" id="cprBtn" class="px-8 py-3 bg-red-600 hover:bg-red-500 rounded-xl font-semibold"><i class="fa-solid fa-play mr-2"></i>Start</button><button onclick="resetCPR()" class="px-6 py-3 bg-zinc-800 hover:bg-zinc-700 rounded-xl font-semibold"><i class="fa-solid fa-rotate-right mr-2"></i>Reset</button></div>
</div></div>
<div class="flex-1"><div class="card-s p-6"><h3 class="font-semibold text-lg mb-4">CPR Steps</h3>
<ol class="space-y-3 text-sm"><li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-red-600/20 text-red-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">1</span><span><strong class="text-zinc-200">Check safety</strong></span></li><li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-red-600/20 text-red-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">2</span><span><strong class="text-zinc-200">Check responsiveness</strong></span></li><li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-red-600/20 text-red-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">3</span><span><strong class="text-zinc-200">Call 912</strong></span></li><li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-red-600/20 text-red-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">4</span><span><strong class="text-zinc-200">Hand position</strong></span></li><li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-red-600/20 text-red-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">5</span><span><strong class="text-zinc-200">Compress</strong> - At least 2 inches, 100-120 BPM.</span></li><li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-red-600/20 text-red-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">6</span><span><strong class="text-zinc-200">Full recoil</strong></span></li><li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-red-600/20 text-red-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">7</span><span><strong class="text-zinc-200">Rescue breaths</strong> - 2 breaths after 30 compressions.</span></li><li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-red-600/20 text-red-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">8</span><span><strong class="text-zinc-200">Continue</strong> - Repeat 30:2 until help arrives.</span></li></ol>
<div class="mt-6 p-4 rounded-lg bg-red-600/10 border border-red-600/20"><p class="text-sm text-red-300"><i class="fa-solid fa-circle-info mr-2"></i><strong>Remember:</strong> Push hard, push fast, allow full recoil, minimize interruptions.</p></div></div></div>
</div>
</div>
</div>
</div>

<!-- CONTACTS -->
<div class="page" id="pg-contacts" style="align-items: center; justify-content: center; min-height: 100vh; padding: 0 16px;">
<div class="max-w-4xl w-full">
<div class="mb-6"><h2 class="text-3xl font-bold mb-1">Emergency Contacts</h2><p class="text-zinc-400">One-tap access to emergency numbers</p></div>

<!-- Emergency Services -->
@if($emergencyContacts->count() > 0)
<div class="space-y-4">
    @foreach($emergencyContacts as $contact)
    <div class="bg-[#18181B] border border-zinc-800 rounded-xl p-6 hover:border-red-600/50 transition-all cursor-pointer" onclick="callContact('{{ $contact->phone }}', '{{ $contact->name }}')">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-xl bg-red-600/20 flex items-center justify-center mr-4">
                    <i class="fa-solid {{ $contact->icon ?? 'fa-phone' }} text-red-400 text-lg"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-white text-lg">{{ $contact->name }}</h4>
                    <p class="text-zinc-300">
                        <i class="fa-solid fa-phone mr-2"></i>{{ $contact->phone }}
                    </p>
                </div>
            </div>
            <button onclick="event.stopPropagation(); callContact('{{ $contact->phone }}', '{{ $contact->name }}')" class="px-6 py-3 bg-red-600 hover:bg-red-500 text-white rounded-lg transition-colors font-semibold">
                <i class="fa-solid fa-phone mr-2"></i>Call
            </button>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="text-center py-12">
    <i class="fa-solid fa-phone text-4xl text-zinc-600 mb-4"></i>
    <p class="text-zinc-400">No emergency contacts available</p>
</div>
@endif

<!-- Rwanda Doctors Network -->
@if($doctors->count() > 0)
<div>
    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
        <i class="fa-solid fa-user-doctor text-blue-400 mr-2"></i>
        Rwanda Doctors Network
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($doctors as $doctor)
        <div class="bg-[#18181B] border border-zinc-800 rounded-xl p-6 hover:border-blue-600/50 transition-all">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-xl bg-blue-600/20 flex items-center justify-center mr-3">
                        <i class="fa-solid fa-user-doctor text-blue-400 text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white">Dr. {{ $doctor->full_name }}</h4>
                        <span class="text-xs text-blue-400 bg-blue-600/20 px-2 py-1 rounded-full">{{ $doctor->specialty }}</span>
                        <div class="text-xs text-zinc-500 mt-1">{{ $doctor->hospital_clinic }}</div>
                    </div>
                </div>
                @if($doctor->is_available)
                    <span class="text-xs text-green-400 bg-green-600/20 px-2 py-1 rounded-full">Available</span>
                @else
                    <span class="text-xs text-zinc-500 bg-zinc-600/20 px-2 py-1 rounded-full">Unavailable</span>
                @endif
            </div>
            <div class="space-y-2 mb-4">
                <div class="text-sm text-zinc-300">
                    <i class="fa-solid fa-map-marker-alt mr-2"></i>{{ $doctor->location }}
                </div>
                <div class="text-sm text-zinc-300">
                    <i class="fa-solid fa-phone mr-2"></i>{{ $doctor->phone }}
                </div>
            </div>
            <div class="border-t border-zinc-800 pt-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                    <div class="text-sm">
                        <span class="text-zinc-500">Specialty:</span> 
                        <span class="text-zinc-300">{{ $doctor->specialty }}</span>
                    </div>
                    <div class="text-sm">
                        <span class="text-zinc-500">Hospital:</span> 
                        <span class="text-zinc-300">{{ $doctor->hospital_clinic }}</span>
                    </div>
                    @if($doctor->whatsapp)
                    <div class="text-sm">
                        <span class="text-zinc-500">WhatsApp:</span> 
                        <span class="text-zinc-300">{{ $doctor->whatsapp }}</span>
                    </div>
                    @endif
                    <div class="text-sm">
                        <span class="text-zinc-500">Status:</span> 
                        <span class="text-zinc-300">{{ $doctor->is_available ? 'Available' : 'Unavailable' }}</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button onclick="callContact('{{ $doctor->phone }}', 'Dr. {{ $doctor->full_name }}')" class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg transition-colors text-sm">
                        <i class="fa-solid fa-phone mr-1"></i>Call
                    </button>
                    @if($doctor->whatsapp)
                    <button onclick="callContact('{{ $doctor->whatsapp }}', 'Dr. {{ $doctor->full_name }} (WhatsApp)')" class="px-3 py-1 bg-green-600 hover:bg-green-500 text-white rounded-lg transition-colors text-sm">
                        <i class="fab fa-whatsapp mr-1"></i>WhatsApp
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<div class="text-center py-12">
    <i class="fa-solid fa-user-doctor text-4xl text-zinc-600 mb-4"></i>
    <p class="text-zinc-400">No doctors available at the moment</p>
</div>
@endif

<!-- System Users section removed -->
</div>
</div>

</main>
</div>

<!-- MOBILE BAR -->
<div class="mob-bar fixed bottom-0 left-0 right-0 bg-black/90 border-t border-zinc-800/50 backdrop-blur-lg z-[4000] px-2 py-2 justify-around items-center" style="display:none">
<button onclick="goTo('dashboard')" class="flex flex-col items-center gap-1 px-3 py-1 rounded-lg text-red-400"><i class="fa-solid fa-house-medical text-lg"></i><span class="text-[10px]">Home</span></button>
<button onclick="goTo('bodymap')" class="flex flex-col items-center gap-1 px-3 py-1 rounded-lg text-zinc-500"><i class="fa-solid fa-person text-lg"></i><span class="text-[10px]">Body</span></button>
<button onclick="goTo('checker')" class="flex flex-col items-center gap-1 px-3 py-1 rounded-lg text-zinc-500"><i class="fa-solid fa-stethoscope text-lg"></i><span class="text-[10px]">Check</span></button>
<button onclick="goTo('guide')" class="flex flex-col items-center gap-1 px-3 py-1 rounded-lg text-zinc-500"><i class="fa-solid fa-book-medical text-lg"></i><span class="text-[10px]">Guide</span></button>
<button onclick="goTo('cpr')" class="flex flex-col items-center gap-1 px-3 py-1 rounded-lg text-zinc-500"><i class="fa-solid fa-heart-pulse text-lg"></i><span class="text-[10px]">CPR</span></button>
<button onclick="goTo('contacts')" class="flex flex-col items-center gap-1 px-3 py-1 rounded-lg text-zinc-500"><i class="fa-solid fa-phone text-lg"></i><span class="text-[10px]">Contacts</span></button>
</div>

<script>
// Duplicate goTo function removed - using the one defined earlier

// Also make it globally accessible
window.goTo = goTo;

// Test that function is available
console.log('goTo function defined:', typeof goTo);

// Ensure function works when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, goTo function available:', typeof goTo);
    // Make sure function is still available after DOM is ready
    window.goTo = goTo;
});

// Load comprehensive emergency conditions from backend
var CONDITIONS = [];
var CATEGORIES = {};

// Initialize comprehensive emergency database
async function loadEmergencyData() {
    try {
        const response = await fetch('/api/emergency-conditions');
        const data = await response.json();
        CONDITIONS = data.conditions;
        CATEGORIES = data.categories;
        
        // Initialize UI after data loads
        initializeUI();
    } catch (error) {
        console.error('Failed to load emergency data:', error);
        // Fallback to basic conditions if API fails
        CONDITIONS = [
            {id:'cardiac-arrest',name:'Cardiac Arrest',icon:'fa-heart-crack',severity:'critical',category:'cardiac',summary:'Sudden loss of heart function.',steps:['Call 912','Begin CPR: 30 compressions to 2 breaths','Push hard and fast','Use AED if available','Continue CPR until help arrives'],dos:['Start CPR immediately','Use AED as soon as possible'],donts:['Do not delay CPR','Do not stop CPR once started'],call912:true}
        ];
        initializeUI();
    }
}

function initializeUI() {
    // Only render if data is loaded
    if (CONDITIONS.length > 0) {
        renderGuide();
        // renderContacts() is now handled by Blade templates - no longer needed
        // renderEmergencyContacts() is now handled by Blade templates - no longer needed
        console.log('Emergency data loaded:', CONDITIONS.length + ' conditions');
    } else {
        console.log('No emergency data available');
    }
}

// Load emergency data when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadEmergencyData();
});

// ZONES object removed due to syntax errors - will be loaded from backend instead
var ZONES = {};

// CONTACTS array removed - Emergency Contacts are now handled only by Blade templates on the contacts page
var CONTACTS = [];

// initializeEmergencyContacts function removed - Emergency Contacts are now handled only by Blade templates

// Render emergency contacts
function renderEmergencyContacts() {
    // COMPLETELY DISABLED - Contacts are now rendered using Blade templates
    // This function was causing contacts to appear on all pages
    // All code below is commented out to prevent execution
    
    /*
    const contacts = initializeEmergencyContacts();
    const grid = document.getElementById('emergencyContactsGrid');
    
    if (!grid) return;
    
    let html = '';
    contacts.forEach(contact => {
        html += `
            <div class="bg-[#18181B] border border-zinc-800 rounded-xl p-6 hover:border-red-600/50 transition-all">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-xl bg-red-600/20 flex items-center justify-center mr-3">
                            <i class="fa-solid ${contact.icon || 'fa-phone'} text-red-400 text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-white">${contact.name}</h4>
                            <span class="text-xs text-red-400 bg-red-600/20 px-2 py-1 rounded-full">${contact.type || 'Emergency'}</span>
                        </div>
                    </div>
                    ${!contact.isDefault ? `
                        <button onclick="deleteEmergencyContact('${contact.id}', '${contact.name}')" class="text-red-400 hover:text-red-300 transition-colors">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    ` : ''}
                </div>
                <div class="flex items-center justify-between">
                    <div class="text-zinc-300">
                        <i class="fa-solid fa-phone mr-2"></i>${contact.phone}
                    </div>
                    <div class="flex gap-2">
                        <button onclick="callContact('${contact.phone}', '${contact.name}')" class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg transition-colors">
                            <i class="fa-solid fa-phone mr-2"></i>Call
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    grid.innerHTML = html;
    */
}

// Delete emergency contact
function deleteEmergencyContact(id, name) {
    if (confirm(`Delete ${name} from your emergency contacts?`)) {
        fetch('/dashboard/contacts/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                location.reload();
            } else {
                alert('Error deleting contact: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting contact');
        });
    }
}


// showGuideDetails function moved to earlier script section to fix loading order issue
// Duplicate callEmergency function removed - now uses database contacts

// closeModal function moved to proper location - removed duplicate that just reloaded page

console.log('Dashboard loaded at:', new Date().toISOString());

function renderContacts(){
    // DISABLED - Contacts are now rendered using Blade templates
    // This function was causing contacts to appear on all pages
    return;
}

function callContact(phone, name){
    if(confirm('Call '+name+' at '+phone+'?')){
        window.location.href='tel:'+phone;
    }
}


function deleteContact(id, name){
    if(confirm('Delete '+name+' from your emergency contacts?')){
        fetch('/dashboard/contacts/'+id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                location.reload();
            } else {
                alert('Error deleting contact: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting contact');
        });
    }
}

function toggleDoctorDetails(index){
    console.log('toggleDoctorDetails called with index:', index);
    var detailsDiv = document.getElementById('details-'+index);
    var chevron = document.getElementById('chevron-'+index);
    
    console.log('detailsDiv:', detailsDiv);
    console.log('chevron:', chevron);
    
    if(detailsDiv.classList.contains('hidden')){
        // Close all other open details
        document.querySelectorAll('[id^="details-"]').forEach(function(el){
            el.classList.add('hidden');
        });
        document.querySelectorAll('[id^="chevron-"]').forEach(function(el){
            el.classList.remove('rotate-180');
        });
        
        // Open this detail
        detailsDiv.classList.remove('hidden');
        chevron.classList.add('rotate-180');
    } else {
        // Close this detail
        detailsDiv.classList.add('hidden');
        chevron.classList.remove('rotate-180');
    }
}

function toast(m){var c=document.getElementById('toastWrap');var d=document.createElement('div');d.className='toast flex items-center gap-3';d.innerHTML='<i class="fa-solid fa-circle-check text-green-400"></i><span>'+m+'</span>';c.appendChild(d);setTimeout(function(){d.remove()},3000)}
function openSOS(){document.getElementById('sosOverlay').classList.add('open')}
function closeSOS(){document.getElementById('sosOverlay').classList.remove('open')}
function openModal(h){document.getElementById('modalBox').innerHTML=h;document.getElementById('modalBg').classList.add('open')}
// closeModal function moved to earlier script section to fix loading order issue

function showZone(z){document.querySelectorAll('.body-zone').forEach(function(e){e.classList.remove('active')});event.target.classList.add('active');var zn=ZONES[z];if(!zn)return;var h='<div class="card-s p-6"><div class="flex items-center gap-3 mb-5"><div class="w-3 h-3 rounded-full" style="background:'+zn.color+'"></div><h3 class="font-bold text-xl">'+zn.label+'</h3></div><div class="space-y-4">';zn.conditions.forEach(function(c){h+='<div class="p-4 rounded-lg bg-zinc-900/50 border border-zinc-800/50"><div class="flex items-start justify-between mb-2"><h4 class="font-semibold text-sm">'+c.name+'</h4><span class="sev-'+c.severity+' text-[11px] px-2 py-0.5 rounded-full font-medium">'+c.severity+'</span></div><p class="text-zinc-400 text-xs mb-3">'+c.desc+'</p><div class="p-3 rounded-md bg-zinc-800/50"><p class="text-xs font-semibold text-teal-400 mb-1"><i class="fa-solid fa-kit-medical mr-1"></i> First Aid:</p><p class="text-xs text-zinc-300">'+c.action+'</p></div></div></div>'});h+='</div></div>';document.getElementById('zonePanel').innerHTML=h}

var activeCat='all';
function renderGuide(f){f=(f||'').toLowerCase();var fl=CONDITIONS;if(activeCat!=='all')fl=fl.filter(function(c){return c.category===activeCat});if(f)fl=fl.filter(function(c){return c.name.toLowerCase().indexOf(f)!==-1||c.summary.toLowerCase().indexOf(f)!==-1});var g=document.getElementById('guideGrid');if(!fl.length){g.innerHTML='<div class="col-span-full text-center py-12 text-zinc-500"><i class="fa-solid fa-search text-3xl mb-3 block"></i><p>No matches found</p></div>';return}g.innerHTML=fl.map(function(c){return '<div class="card p-4 cursor-pointer hover:border-red-500/30 transition-all" onclick="showCM(\''+c.id+'\')"><div class="flex items-start justify-between mb-3"><div class="flex items-center gap-3"><div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center"><i class="fa-solid '+c.icon+' text-red-400"></i></div><div><h4 class="font-semibold text-sm">'+c.name+'</h4><p class="text-[11px] text-zinc-500 capitalize">'+c.category+'</p></div></div><span class="sev-'+c.severity+' text-[10px] px-2 py-0.5 rounded-full font-medium">'+c.severity+'</span></div><p class="text-xs text-zinc-400 line-clamp-2">'+c.summary+'</p><div class="flex items-center gap-2 mt-3 text-[11px] text-zinc-500"><span><i class="fa-solid fa-list-ol mr-1"></i>'+c.steps.length+' steps</span><span class="w-1 h-1 rounded-full bg-zinc-700"></span>'+(c.call912?'<span class="text-red-400"><i class="fa-solid fa-phone-volume mr-1"></i>Call 912</span>':'<span>Self-care</span>')+'</div></div>'}).join('')}
function filterGuide(v){renderGuide(v)}
function filterCat(c){activeCat=c;renderGuide();var bs=document.querySelectorAll('#guideFilters button');bs.forEach(function(b){b.className='px-3 py-1.5 rounded-lg text-xs font-medium bg-zinc-800 text-zinc-400 border border-transparent hover:border-zinc-700'});event.target.className='px-3 py-1.5 rounded-lg text-xs font-medium bg-red-600/20 text-red-400 border border-red-600/30'}
function showCM(id){var c=CONDITIONS.find(function(x){return x.id===id});if(!c)return;var sh=c.steps.map(function(s,i){return '<li class="flex gap-3 text-sm"><span class="w-6 h-6 rounded-full bg-red-600/20 text-red-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">'+(i+1)+'</span><span class="text-zinc-300">'+s+'</span></li>'}).join('');var dh=c.dos.map(function(d){return '<li class="text-xs text-zinc-300">'+d+'</li>'}).join('');var nh=c.donts.map(function(d){return '<li class="text-xs text-zinc-300">'+d+'</li>'}).join('');var h='<div class="flex items-center justify-between p-5 border-b border-zinc-800"><h3 class="font-semibold text-lg">'+c.name+'</h3><button onclick="closeModal()" class="w-8 h-8 rounded-lg hover:bg-zinc-800 flex items-center justify-center"><i class="fa-solid fa-xmark text-zinc-400"></i></button></div><div class="p-6"><div class="flex items-center gap-3 mb-4"><span class="sev-'+c.severity+' text-xs px-3 py-1 rounded-full font-semibold uppercase">'+c.severity+'</span>'+(c.call912?'<span class="text-xs px-3 py-1 rounded-full bg-red-600/20 text-red-400 border border-red-600/30 font-semibold">Call 912</span>':'<span class="text-xs px-3 py-1 rounded-full bg-zinc-800 text-zinc-400">Self-care</span>')+'</div><p class="text-zinc-300 text-sm mb-5">'+c.summary+'</p><h4 class="font-semibold text-sm text-teal-400 mb-3"><i class="fa-solid fa-list-ol mr-2"></i>Steps</h4><ol class="space-y-2 mb-6">'+sh+'</ol><div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div class="p-4 rounded-lg bg-green-600/5 border border-green-600/15"><h5 class="text-xs font-bold text-green-400 mb-2"><i class="fa-solid fa-check mr-1"></i> DO</h5><ul class="space-y-1.5">'+dh+'</ul></div><div class="p-4 rounded-lg bg-red-600/5 border border-red-600/15"><h5 class="text-xs font-bold text-red-400 mb-2"><i class="fa-solid fa-xmark mr-1"></i> DON\'T</h5><ul class="space-y-1.5">'+nh+'</ul></div></div></div>';openModal(h)}

// checkerQ1 will be initialized when DOM is ready
function renderContacts(){var h='';CONTACTS.forEach(function(c){h+='<div class="card p-5"><div class="flex items-center gap-4"><div class="w-12 h-12 rounded-full bg-zinc-800 flex items-center justify-center"><i class="fa-solid '+c.icon+' text-teal-400"></i></div><div class="flex-1"><h4 class="font-semibold text-sm">'+c.name+'</h4><p class="text-zinc-400 text-xs">'+c.type+'</p></div><a href="tel:'+c.phone+'" class="px-4 py-2 bg-teal-600 hover:bg-teal-500 rounded-lg text-sm font-semibold text-white transition-colors"><i class="fa-solid fa-phone mr-2"></i>Call</a></div></div>'});document.getElementById('contactsGrid').innerHTML=h}
// Emergency Search Functionality with AI Recommendations
function performSearch(query) {
    const searchResults = document.getElementById('searchResults');
    if (!searchResults) return;
    
    console.log('Searching for:', query);
    
    // Show loading state
    searchResults.innerHTML = `
        <div class="p-4 text-center text-zinc-400">
            <div class="inline-block animate-spin w-6 h-6 border-2 border-zinc-600 border-t-red-400 rounded-full mb-2"></div>
            <p>Getting AI recommendations...</p>
        </div>
    `;
    searchResults.classList.remove('hidden');
    
    // Get AI recommendations
    console.log('performSearch called with query:', query);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    console.log('CSRF Token found:', csrfToken ? 'Yes' : 'No');
    
    fetch('/emergency-ai/recommendations', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ query: query })
    })
    .then(response => {
        console.log('Response received, status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success && data.data) {
            displayAIRecommendations(data.data, query);
        } else {
            console.log('AI failed, using fallback search');
            // Fallback to local search if AI fails
            performFallbackSearch(query);
        }
    })
    .catch(error => {
        console.error('AI search error:', error);
        console.log('Error caught, using fallback search');
        // Fallback to local search
        performFallbackSearch(query);
    });
}

// Fallback search using local conditions
function performFallbackSearch(query) {
    const searchResults = document.getElementById('searchResults');
    if (!searchResults) return;
    
    // Filter conditions based on query
    const results = CONDITIONS.filter(condition => {
        const searchText = query.toLowerCase();
        return (
            condition.name.toLowerCase().includes(searchText) ||
            condition.summary.toLowerCase().includes(searchText) ||
            condition.category.toLowerCase().includes(searchText) ||
            (condition.steps && condition.steps.some(step => step.toLowerCase().includes(searchText)))
        );
    });
    
    displayEmergencySearchResults(results, query);
}

// Display AI-powered recommendations
function displayAIRecommendations(aiData, query) {
    const searchResults = document.getElementById('searchResults');
    if (!searchResults) return;
    
    const recommendations = aiData.recommendations || [];
    
    if (recommendations.length === 0) {
        searchResults.innerHTML = `
            <div class="p-4 text-center text-zinc-400">
                <i class="fa-solid fa-search mb-2 text-2xl"></i>
                <p>No AI recommendations found for "${query}".</p>
            </div>
        `;
        return;
    }
    
    let html = '';
    
    // Add AI badge if powered by AI
    if (aiData.aiPowered) {
        html += `
            <div class="p-3 bg-green-600/10 border-b border-zinc-800">
                <div class="flex items-center gap-2 text-green-400 text-sm">
                    <i class="fa-solid fa-brain"></i>
                    <span>AI-Powered Recommendations</span>
                </div>
            </div>
        `;
    }
    
    // Display each recommendation
    recommendations.forEach(rec => {
        const severityColor = rec.severity === 'critical' ? 'red' : 
                             rec.severity === 'urgent' ? 'orange' : 
                             rec.severity === 'moderate' ? 'yellow' : 'green';
        
        html += `
            <div class="p-4 border-b border-zinc-800 hover:bg-zinc-800/50 cursor-pointer transition-colors" onclick="showAIRecommendationDetails(this)" data-rec='${JSON.stringify(rec).replace(/'/g, "&apos;")}' data-ai='${JSON.stringify(aiData).replace(/'/g, "&apos;")}'>
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-heart-pulse text-${severityColor}-400"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <h4 class="font-semibold text-white">${rec.condition}</h4>
                            <span class="sev-${rec.severity} text-xs px-2 py-1 rounded-full font-semibold uppercase">
                                ${rec.severity}
                            </span>
                            ${rec.callEmergency ? '<span class="text-xs px-2 py-1 bg-red-600/20 border border-red-600/30 rounded-full font-semibold text-red-300">CALL ' + aiData.emergencyNumber + '</span>' : ''}
                        </div>
                        <p class="text-sm text-zinc-400 mb-2">${rec.summary}</p>
                        <div class="flex items-center gap-4 text-xs text-zinc-500">
                            <span><i class="fa-solid fa-list-ol mr-1"></i>${rec.immediateActions.length} immediate actions</span>
                            ${rec.callEmergency ? '<span><i class="fa-solid fa-phone-volume mr-1"></i>Emergency call required</span>' : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    // Add disclaimer
    if (aiData.disclaimer) {
        html += `
            <div class="p-3 bg-zinc-800/50 border-t border-zinc-800">
                <p class="text-xs text-zinc-500 text-center">
                    <i class="fa-solid fa-info-circle mr-1"></i>
                    ${aiData.disclaimer}
                </p>
            </div>
        `;
    }
    
    searchResults.innerHTML = html;
    searchResults.classList.remove('hidden');
}

// Show AI recommendation details in modal
function showAIRecommendationDetails(element) {
    try {
        const recommendation = JSON.parse(element.getAttribute('data-rec').replace(/&apos;/g, "'"));
        const aiInfo = JSON.parse(element.getAttribute('data-ai').replace(/&apos;/g, "'"));
        
        const severityColor = recommendation.severity === 'critical' ? 'red' : 
                             recommendation.severity === 'urgent' ? 'orange' : 
                             recommendation.severity === 'moderate' ? 'yellow' : 'green';
        
        const actionsHtml = recommendation.immediateActions.map((action, index) => 
            `<li class="flex gap-3 text-sm">
                <span class="w-6 h-6 rounded-full bg-${severityColor}-600/20 text-${severityColor}-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">${index + 1}</span>
                <span class="text-zinc-300">${action}</span>
            </li>`
        ).join('');
        
        const emergencySignsHtml = recommendation.emergencySigns ? 
            recommendation.emergencySigns.map(sign => 
                `<li class="text-xs text-zinc-300 flex items-center gap-2">
                    <i class="fa-solid fa-exclamation-triangle text-${severityColor}-400"></i>
                    ${sign}
                </li>`
            ).join('') : '';
        
        const modalHTML = `
            <div class="flex items-center justify-between p-5 border-b border-zinc-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-green-600/20 flex items-center justify-center">
                        <i class="fa-solid fa-brain text-green-400"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg">AI Emergency Recommendation</h3>
                        <span class="text-xs text-green-300">${aiInfo.aiPowered ? 'Powered by Google AI' : 'Fallback System'}</span>
                    </div>
                </div>
                <button onclick="closeModal()" class="w-8 h-8 rounded-lg hover:bg-zinc-800 flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-zinc-400"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="sev-${recommendation.severity} text-xs px-3 py-1 rounded-full font-semibold uppercase">
                        ${recommendation.severity}
                    </span>
                    ${recommendation.callEmergency ? `<span class="text-xs px-3 py-1 bg-red-600/20 border border-red-600/30 rounded-full font-semibold text-red-300">CALL ${aiInfo.emergencyNumber}</span>` : ''}
                </div>
                
                <h4 class="font-semibold text-white mb-2">${recommendation.condition}</h4>
                <p class="text-zinc-300 text-sm mb-5">${recommendation.summary}</p>
                
                <h4 class="font-semibold text-sm text-${severityColor}-400 mb-3">
                    <i class="fa-solid fa-list-ol mr-2"></i>Immediate Actions
                </h4>
                <ol class="space-y-2 mb-6">${actionsHtml}</ol>
                
                ${emergencySignsHtml ? `
                    <h4 class="font-semibold text-sm text-orange-400 mb-3">
                        <i class="fa-solid fa-exclamation-triangle mr-2"></i>Emergency Signs
                    </h4>
                    <ul class="space-y-1.5 mb-6">${emergencySignsHtml}</ul>
                ` : ''}
                
                <div class="p-4 rounded-lg bg-yellow-600/10 border border-yellow-600/20">
                    <p class="text-xs text-yellow-300">
                        <i class="fa-solid fa-info-circle mr-1"></i>
                        ${aiInfo.disclaimer}
                    </p>
                </div>
            </div>
        `;
        
        openModal(modalHTML);
    } catch (error) {
        console.error('Error showing AI recommendation details:', error);
    }
}

function displayEmergencySearchResults(results, query) {
    const searchResults = document.getElementById('searchResults');
    if (!searchResults) return;
    
    if (results.length === 0) {
        searchResults.innerHTML = `
            <div class="p-4 text-center text-zinc-400">
                <i class="fa-solid fa-search mb-2 text-2xl"></i>
                <p>No emergencies found for "${query}". Try different keywords.</p>
            </div>
        `;
    } else {
      searchResults.innerHTML = results.slice(0, 5).map(condition => `
        <div class="p-4 border-b border-zinc-800 hover:bg-zinc-800/50 cursor-pointer transition-colors" onclick="goTo('guide')">
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0">
              <i class="fa-solid ${condition.icon} text-red-400"></i>
            </div>
            <div class="flex-1">
              <div class="flex items-center gap-2 mb-1">
                <h4 class="font-semibold text-white">${condition.name}</h4>
                <span class="sev-${condition.severity} text-xs px-2 py-1 rounded-full font-semibold uppercase">
                  ${condition.severity}
                </span>
              </div>
              <p class="text-sm text-zinc-400 mb-2">${condition.summary}</p>
              <div class="flex items-center gap-4 text-xs text-zinc-500">
                <span><i class="fa-solid fa-list-ol mr-1"></i>${condition.steps.length} steps</span>
                ${condition.call912 ? '<span><i class="fa-solid fa-phone-volume mr-1"></i>Call 912</span>' : ''}
              </div>
            </div>
          </div>
        </div>
      `).join('');
    }
    
    searchResults.classList.remove('hidden');
  }
  
  // Close search results when clicking outside
  document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
      searchResults.classList.add('hidden');
    }
  });
}


// Enhanced search input handler
    const recommendation = typeof data === 'string' ? JSON.parse(data) : data;
    console.log('Parsed recommendation:', recommendation);
    
    const modalHTML = `
      <div class="flex items-center justify-between p-5 border-b border-zinc-800">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-green-600/20 flex items-center justify-center">
            <i class="fa-solid fa-brain text-green-400"></i>
          </div>
          <div>
            <h3 class="font-semibold text-lg">Free AI First Aid Recommendation</h3>
            <span class="text-xs text-green-300">Powered by Free AI Service</span>
          </div>
        </div>
        <button onclick="closeModal()" class="w-8 h-8 rounded-lg hover:bg-zinc-800 flex items-center justify-center">
          <i class="fa-solid fa-xmark text-zinc-400"></i>
        </button>
      </div>
      <div class="p-6">
        <div class="flex items-center gap-3 mb-4">
          <span class="px-3 py-1 bg-${recommendation.emergency_level === 'critical' ? 'red' : recommendation.emergency_level === 'urgent' ? 'orange' : 'blue'}-600/20 border border-${recommendation.emergency_level === 'critical' ? 'red' : recommendation.emergency_level === 'urgent' ? 'orange' : 'blue'}-600/30 rounded-full text-sm font-semibold text-${recommendation.emergency_level === 'critical' ? 'red' : recommendation.emergency_level === 'urgent' ? 'orange' : 'blue'}-300">
            ${recommendation.emergency_level.toUpperCase()} EMERGENCY
          </span>
          ${recommendation.emergency_call ? '<span class="px-3 py-1 bg-red-600/20 border border-red-600/30 rounded-full text-sm font-semibold text-red-300">CALL 912</span>' : ''}
        </div>
        
        <div class="mb-6">
          <h4 class="font-semibold text-lg text-white mb-2">${recommendation.condition_name}</h4>
          <div class="bg-red-600/10 border border-red-600/20 rounded-xl p-4">
            <div class="flex items-center gap-2 mb-2">
              <i class="fa-solid fa-exclamation-triangle text-red-400"></i>
              <h5 class="font-semibold text-red-300">IMMEDIATE ACTION</h5>
            </div>
            <p class="text-white font-medium">${recommendation.immediate_action}</p>
          </div>
        </div>
        
        <div class="mb-6">
          <h5 class="font-semibold text-white mb-3 flex items-center gap-2">
            <i class="fa-solid fa-list-ol text-blue-400"></i>
            Step-by-Step Instructions
          </h5>
          <div class="space-y-3">
            ${recommendation.steps && recommendation.steps.length > 0 ? recommendation.steps.map((step, index) => `
              <div class="flex gap-3">
                <div class="w-6 h-6 rounded-full bg-blue-600/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <span class="text-xs text-blue-400 font-semibold">${index + 1}</span>
                </div>
                <p class="text-zinc-300 text-sm leading-relaxed">${step || 'Step not available'}</p>
              </div>
            `).join('') : '<div class="text-zinc-400 text-sm">No detailed steps available</div>'}
          </div>
        </div>
        
        ${recommendation.warning_signs && recommendation.warning_signs.length > 0 ? `
          <div class="mb-6 p-4 rounded-lg bg-orange-600/10 border border-orange-600/20">
            <h5 class="font-semibold text-orange-300 mb-2">
              <i class="fa-solid fa-triangle-exclamation mr-2"></i>
              Warning Signs
            </h5>
            <ul class="space-y-1">
              ${recommendation.warning_signs.map(sign => `<li class="text-sm text-zinc-300">• ${sign}</li>`).join('')}
            </ul>
          </div>
        ` : ''}
        
        ${recommendation.important_notes && recommendation.important_notes.length > 0 ? `
          <div class="mb-6 p-4 rounded-lg bg-blue-600/10 border border-blue-600/20">
            <h5 class="font-semibold text-blue-300 mb-2">
              <i class="fa-solid fa-circle-info mr-2"></i>
              Important Notes
            </h5>
            <ul class="space-y-1">
              ${recommendation.important_notes.map(note => `<li class="text-sm text-zinc-300">• ${note}</li>`).join('')}
            </ul>
          </div>
        ` : ''}
        
        <div class="flex gap-3">
          <button onclick="closeModal()" class="flex-1 px-4 py-2 bg-zinc-800 hover:bg-zinc-700 rounded-lg font-semibold transition-colors">
            Close
          </button>
          ${recommendation.emergency_call ? '<button onclick="openSOS()" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg text-center font-semibold transition-colors"><i class="fa-solid fa-phone-volume mr-2"></i>Call Emergency Services</button>' : ''}
        </div>
      </div>
    `;
    
    openModal(modalHTML);
  } catch (error) {
    console.error('Error showing AI details:', error);
    toast('Error displaying AI recommendations');
  }
}

// Enhanced search input handler with AI integration
if (searchInput && searchResults) {
  let searchTimeout;
  
  searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const query = this.value.toLowerCase().trim();
    
    if (query.length < 2) {
      searchResults.classList.add('hidden');
      return;
    }
    
    // Debounce AI search
    searchTimeout = setTimeout(() => {
      performSearch(query);
    }, 500);
  });
  
  // Add Enter key support
  searchInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
      clearTimeout(searchTimeout);
      const query = this.value.toLowerCase().trim();
      
      if (query.length >= 2) {
        performSearch(query);
      }
    }
  });
}

// Final defensive check - ensure all critical functions are available
if (typeof window.goTo === 'undefined') {
    console.error('CRITICAL: goTo function is not available!');
    window.goTo = function(pg) {
        console.error('goTo function called but not properly initialized');
        alert('Navigation error - please refresh the page');
    };
} else {
    console.log('All functions properly loaded before DOM ready');
}

// Initialize page functionality
document.addEventListener('DOMContentLoaded', function() {
  console.log('Dashboard loaded successfully');
  
  // Setup emergency search immediately
  if (!setupEmergencySearch()) {
    console.log('Emergency search setup failed, retrying...');
    setTimeout(setupEmergencySearch, 500);
  }
  
  // Initialize checkerQ1 with multiple attempts
  function initializeCheckerQ1() {
    const checkerElement = document.getElementById('pg-checker');
    if (checkerElement && checkerElement.innerHTML) {
      window.checkerQ1 = checkerElement.innerHTML;
      console.log('checkerQ1 initialized successfully, length:', window.checkerQ1.length);
      return true;
    } else {
      console.log('pg-checker element not ready, will retry...');
      return false;
    }
  }
  
  // Try immediately
  if (!initializeCheckerQ1()) {
    // Retry after a short delay
    setTimeout(initializeCheckerQ1, 100);
  }
  
  // Test that JavaScript is running
  console.log('Emergency search JavaScript is loading...');
  
  // Setup search input event listener
  const searchInput = document.getElementById('emergencySearch');
  const searchResults = document.getElementById('searchResults');
  
  console.log('Setting up search functionality...');
  console.log('Search input found:', searchInput ? 'Yes' : 'No');
  console.log('Search results found:', searchResults ? 'Yes' : 'No');
  
  if (searchInput && searchResults) {
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
      clearTimeout(searchTimeout);
      const query = this.value.toLowerCase().trim();
      
      if (query.length >= 2) {
        searchTimeout = setTimeout(() => {
          performSearch(query);
        }, 300);
      } else {
        // Hide results if query is too short
        searchResults.classList.add('hidden');
      }
    });
    
    // Handle Enter key for search
    searchInput.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        const query = this.value.trim();
        if (query.length >= 2) {
          performSearch(query);
        }
      }
    });
    
    // Close search results when clicking outside
    document.addEventListener('click', function(e) {
      if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.classList.add('hidden');
      }
    });
  }
});

// Only render when on appropriate pages
function renderContentForActivePage() {
    const activePage = document.querySelector('.page.active');
    if (activePage && activePage.id === 'pg-guide') {
        renderGuide();
    }
    // renderContacts() is now handled by Blade templates - no longer needed
}

// Call the function after a short delay to ensure pages are loaded
setTimeout(renderContentForActivePage, 100);
</script>
</body>
</html>
