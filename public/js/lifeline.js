/**
 * LifeLine — Global JavaScript
 * Toast notifications, voice synthesis, condition detail helper
 */

// Toast notification system
function showToast(message, type) {
    type = type || 'info';
    var container = document.getElementById('toastContainer');
    if (!container) return;

    var toast = document.createElement('div');
    var icons = {
        info: 'fa-circle-info',
        success: 'fa-circle-check',
        warning: 'fa-triangle-exclamation',
        error: 'fa-circle-xmark'
    };
    var colors = {
        info: 'text-teal-400',
        success: 'text-green-400',
        warning: 'text-yellow-400',
        error: 'text-red-400'
    };

    toast.className = 'flex items-center gap-3';
    toast.innerHTML = '<i class="fa-solid ' + (icons[type] || icons.info) + ' ' + (colors[type] || colors.info) + '"></i><span>' + message + '</span>';
    container.appendChild(toast);
    setTimeout(function () { toast.remove(); }, 3000);
}

// Text-to-speech helper
function speak(text) {
    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
        var utterance = new SpeechSynthesisUtterance(text);
        utterance.rate = 0.95;
        utterance.pitch = 1;
        utterance.volume = 1;
        window.speechSynthesis.speak(utterance);
    }
}

// Show condition detail from dashboard quick actions
// Fetches from API and opens a modal dynamically
function showCondition(conditionId) {
    fetch('/api/conditions/' + conditionId)
        .then(function (res) {
            if (!res.ok) throw new Error('Not found');
            return res.json();
        })
        .then(function (c) {
            // Remove existing modal if any
            var existing = document.getElementById('conditionModal');
            if (existing) existing.remove();

            // Build steps HTML
            var stepsHtml = '';
            for (var i = 0; i < c.steps.length; i++) {
                stepsHtml += '<li class="flex gap-3 text-sm">'
                    + '<span class="w-6 h-6 rounded-full bg-red-600/20 text-red-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">' + (i + 1) + '</span>'
                    + '<span class="text-zinc-300 leading-relaxed">' + c.steps[i] + '</span>'
                    + '</li>';
            }

            // Build DOs HTML
            var dosHtml = '';
            for (var d = 0; d < c.dos.length; d++) {
                dosHtml += '<li class="text-xs text-zinc-300 leading-relaxed">' + c.dos[d] + '</li>';
            }

            // Build DON'Ts HTML
            var dontsHtml = '';
            for (var n = 0; n < c.donts.length; n++) {
                dontsHtml += '<li class="text-xs text-zinc-300 leading-relaxed">' + c.donts[n] + '</li>';
            }

            // Build severity class
            var sevClass = 'sev-' + c.severity;

            // Build call912 badge
            var callBadge = c.call912
                ? '<span class="text-xs px-3 py-1 rounded-full bg-red-600/20 text-red-400 border border-red-600/30 font-semibold">Call 912</span>'
                : '';

            // Build speak button action
            var stepsText = c.steps.join('. ');

            // Build full modal
            var modal = document.createElement('div');
            modal.id = 'conditionModal';
            modal.className = 'fixed inset-0 z-[7000] bg-black/70 backdrop-blur-sm flex items-center justify-center p-5';
            modal.onclick = function (e) {
                if (e.target === modal) modal.remove();
            };

            modal.innerHTML = ''
                + '<div class="bg-[#18181B] border border-zinc-800 rounded-2xl max-w-[560px] w-full max-h-[80vh] overflow-y-auto" onclick="event.stopPropagation()">'
                + '  <div class="flex items-center justify-between p-5 border-b border-zinc-800">'
                + '    <h3 class="font-display font-semibold text-lg">' + c.name + '</h3>'
                + '    <button onclick="document.getElementById(\'conditionModal\').remove()" class="w-8 h-8 rounded-lg hover:bg-zinc-800 flex items-center justify-center transition-colors"><i class="fa-solid fa-xmark text-zinc-400"></i></button>'
                + '  </div>'
                + '  <div class="p-6">'
                + '    <div class="flex items-center gap-3 mb-4">'
                + '      <span class="' + sevClass + ' text-xs px-3 py-1 rounded-full font-semibold uppercase">' + c.severity + '</span>'
                + '      ' + callBadge
                + '    </div>'
                + '    <p class="text-zinc-300 text-sm mb-5 leading-relaxed">' + c.summary + '</p>'
                + '    <h4 class="font-display font-semibold text-sm mb-3 text-teal-400"><i class="fa-solid fa-list-ol mr-2"></i>Step-by-Step Instructions</h4>'
                + '    <ol class="space-y-2 mb-6">' + stepsHtml + '</ol>'
                + '    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">'
                + '      <div class="p-4 rounded-lg bg-green-600/5 border border-green-600/15">'
                + '        <h5 class="text-xs font-bold text-green-400 mb-2"><i class="fa-solid fa-check mr-1"></i> DO</h5>'
                + '        <ul class="space-y-1.5">' + dosHtml + '</ul>'
                + '      </div>'
                + '      <div class="p-4 rounded-lg bg-red-600/5 border border-red-600/15">'
                + '        <h5 class="text-xs font-bold text-red-400 mb-2"><i class="fa-solid fa-xmark mr-1"></i> DON\'T</h5>'
                + '        <ul class="space-y-1.5">' + dontsHtml + '</ul>'
                + '      </div>'
                + '    </div>'
                + '    <button onclick="speak(\'' + stepsText.replace(/'/g, "\\'") + '\')" class="w-full px-4 py-2.5 bg-teal-600/20 text-teal-400 rounded-lg hover:bg-teal-600/30 transition-colors text-sm font-semibold">'
                + '      <i class="fa-solid fa-volume-high mr-2"></i>Read Steps Aloud'
                + '    </button>'
                + '  </div>'
                + '</div>';

            document.body.appendChild(modal);
        })
        .catch(function (err) {
            showToast('Failed to load condition details', 'error');
            console.error(err);
        });
}

// Close mobile "More" menu when clicking outside
document.addEventListener('click', function (e) {
    var menu = document.getElementById('moreMenu');
    if (menu && !menu.classList.contains('hidden')) {
        if (!menu.contains(e.target) && !e.target.closest('button[onclick*="moreMenu"]')) {
            menu.classList.add('hidden');
        }
    }
});