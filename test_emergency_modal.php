<!DOCTYPE html>
<html>
<head>
    <title>Test Emergency Modal</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .test-btn { padding: 10px 20px; margin: 10px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .result { margin: 10px 0; padding: 10px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Emergency Modal Test</h1>
    
    <h2>Testing Modal Close Function</h2>
    <button class="test-btn" onclick="testCloseModal()">Test Close Modal</button>
    <div id="test-result" class="result"></div>
    
    <h2>Simulating AI Response</h2>
    <button class="test-btn" onclick="simulateAIResponse()">Simulate AI Response</button>
    
    <script>
        // Simulate the modal structure from your dashboard
        function createModal() {
            const modalBg = document.createElement('div');
            modalBg.id = 'modalBg';
            modalBg.className = 'modal-bg';
            
            const modalBox = document.createElement('div');
            modalBox.id = 'modalBox';
            modalBox.className = 'modal-box';
            
            document.body.appendChild(modalBg);
            document.body.appendChild(modalBox);
        }
        
        function testCloseModal() {
            console.log('Testing modal close...');
            
            // Create modal if it doesn't exist
            if (!document.getElementById('modalBg')) {
                createModal();
            }
            
            // Test the closeModal function
            if (typeof closeModal === 'function') {
                closeModal();
                document.getElementById('test-result').innerHTML = '✅ Modal closed successfully!';
                document.getElementById('test-result').style.color = '#28a745';
            } else {
                document.getElementById('test-result').innerHTML = '❌ closeModal function not found!';
                document.getElementById('test-result').style.color = '#dc3545';
            }
        }
        
        function simulateAIResponse() {
            console.log('Simulating AI response...');
            
            // Create modal if it doesn't exist
            if (!document.getElementById('modalBg')) {
                createModal();
            }
            
            // Simulate AI response data
            const aiData = {
                aiPowered: true,
                condition: 'Test Hand Injury',
                severity: 'urgent',
                summary: 'This is a test AI response for hand injury.',
                recommendations: [{
                    condition: 'Test Hand Injury',
                    severity: 'urgent',
                    summary: 'This is a test AI response for hand injury.',
                    immediateActions: ['Test action 1', 'Test action 2'],
                    callEmergency: false,
                    emergencySigns: ['Test sign 1', 'Test sign 2']
                }]
            };
            
            // Simulate the modal HTML structure
            const modalHTML = `
                <div class="flex items-center justify-between p-5 border-b border-zinc-800">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-green-600/20 flex items-center justify-center">
                            <i class="fa-solid fa-brain text-green-400"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">AI Emergency Recommendation</h3>
                            <span class="text-xs text-green-300">${aiData.aiPowered ? 'Powered by Google AI' : 'Fallback System'}</span>
                        </div>
                        <button onclick="testCloseFunction()" class="w-8 h-8 rounded-lg hover:bg-zinc-800 flex items-center justify-center">
                            <i class="fa-solid fa-xmark text-zinc-400"></i>
                        </button>
                    </div>
                </div>
                <button onclick="testCloseFunction()" class="w-8 h-8 rounded-lg hover:bg-zinc-800 flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-zinc-400"></i>
                </button>
            </div>
        `;
            
            const modalBox = document.getElementById('modalBox');
            const modalBg = document.getElementById('modalBg');
            
            if (modalBox && modalBg) {
                modalBox.innerHTML = modalHTML;
                modalBg.classList.add('open');
                document.body.style.overflow = 'hidden';
                
                console.log('Modal opened with AI data');
            } else {
                console.error('Modal elements not found');
            }
        }
        
        function testCloseFunction() {
            console.log('Close button in modal clicked - testing closeModal function');
            testCloseModal();
        }
        
        // Auto-test on page load
        window.onload = function() {
            setTimeout(() => {
                console.log('Auto-testing modal close...');
                testCloseModal();
            }, 1000);
        };
    </script>
</body>
</html>
