<!-- Welcome Tutorial Modal -->
<div id="welcomeTutorial" class="tutorial-overlay" style="display: none;">
    <div class="tutorial-modal">
        <div class="tutorial-header">
            <h2 class="tutorial-title">Welcome to OGameX, Commander!</h2>
            <button class="tutorial-close" onclick="closeTutorial()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M18 6L6 18M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="tutorial-content">
            <div class="tutorial-step active" data-step="1">
                <div class="step-icon">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 2v20M2 12h20"></path>
                    </svg>
                </div>
                <h3>Your First Planet</h3>
                <p>You've been assigned your home planet <strong id="tutorialPlanetName">{{ $planetName ?? 'Homeworld' }}</strong>. This is your base of operations where you'll build your empire.</p>
                <div class="tutorial-tips">
                    <h4>Quick Start Tips:</h4>
                    <ul>
                        <li>🏭 Build Metal and Crystal mines first for steady income</li>
                        <li>⚡ Upgrade your Solar Plant to power your mines</li>
                        <li>🔬 Start researching basic technologies early</li>
                        <li>🚀 Build a Shipyard to create your first ships</li>
                    </ul>
                </div>
            </div>
            
            <div class="tutorial-step" data-step="2">
                <div class="step-icon">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.01" x2="12" y2="12"></line>
                    </svg>
                </div>
                <h3>Resource Management</h3>
                <p>Resources are the foundation of your empire. You'll need to balance production and consumption carefully.</p>
                <div class="resource-guide">
                    <div class="resource-item">
                        <span class="resource-badge metal">Metal</span>
                        <span>Used for buildings and ships</span>
                    </div>
                    <div class="resource-item">
                        <span class="resource-badge crystal">Crystal</span>
                        <span>Essential for advanced technology</span>
                    </div>
                    <div class="resource-item">
                        <span class="resource-badge deuterium">Deuterium</span>
                        <span>Fuel for ships and research</span>
                    </div>
                    <div class="resource-item">
                        <span class="resource-badge energy">Energy</span>
                        <span>Powers your mines and facilities</span>
                    </div>
                </div>
            </div>
            
            <div class="tutorial-step" data-step="3">
                <div class="step-icon">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </div>
                <h3>Your First Goals</h3>
                <p>Here's what you should focus on in your first days:</p>
                <div class="goals-list">
                    <label class="goal-item">
                        <input type="checkbox" disabled>
                        <span>Build Metal Mine to Level 4</span>
                    </label>
                    <label class="goal-item">
                        <input type="checkbox" disabled>
                        <span>Build Crystal Mine to Level 2</span>
                    </label>
                    <label class="goal-item">
                        <input type="checkbox" disabled>
                        <span>Upgrade Solar Plant for energy</span>
                    </label>
                    <label class="goal-item">
                        <input type="checkbox" disabled>
                        <span>Build a Research Lab</span>
                    </label>
                    <label class="goal-item">
                        <input type="checkbox" disabled>
                        <span>Research Espionage Technology</span>
                    </label>
                    <label class="goal-item">
                        <input type="checkbox" disabled>
                        <span>Build your first Cargo Ship</span>
                    </label>
                </div>
            </div>
        </div>
        
        <div class="tutorial-footer">
            <div class="tutorial-progress">
                <span class="progress-dot active" data-step="1"></span>
                <span class="progress-dot" data-step="2"></span>
                <span class="progress-dot" data-step="3"></span>
            </div>
            <div class="tutorial-actions">
                <button class="btn-tutorial-prev" onclick="previousStep()" style="display: none;">Previous</button>
                <button class="btn-tutorial-next" onclick="nextStep()">Next</button>
                <button class="btn-tutorial-start" onclick="startPlaying()" style="display: none;">Start Playing!</button>
            </div>
        </div>
    </div>
</div>

<style>
.tutorial-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(10px);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease;
}

.tutorial-modal {
    background: linear-gradient(135deg, #1e293b, #0f172a);
    border: 1px solid rgba(59, 130, 246, 0.3);
    border-radius: 16px;
    width: 90%;
    max-width: 600px;
    max-height: 80vh;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
}

.tutorial-header {
    padding: 24px;
    border-bottom: 1px solid rgba(59, 130, 246, 0.2);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.tutorial-title {
    font-family: 'Orbitron', sans-serif;
    font-size: 24px;
    color: #f8fafc;
    margin: 0;
}

.tutorial-close {
    background: none;
    border: none;
    color: #cbd5e1;
    cursor: pointer;
    padding: 8px;
    transition: all 0.2s;
}

.tutorial-close:hover {
    color: #f8fafc;
}

.tutorial-content {
    padding: 32px 24px;
    overflow-y: auto;
    max-height: calc(80vh - 180px);
}

.tutorial-step {
    display: none;
    text-align: center;
}

.tutorial-step.active {
    display: block;
    animation: slideIn 0.3s ease;
}

.step-icon {
    margin: 0 auto 24px;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(139, 92, 246, 0.2));
    border-radius: 50%;
    color: #3b82f6;
}

.tutorial-step h3 {
    font-family: 'Orbitron', sans-serif;
    font-size: 20px;
    color: #f8fafc;
    margin-bottom: 16px;
}

.tutorial-step p {
    color: #cbd5e1;
    line-height: 1.6;
    margin-bottom: 24px;
}

.tutorial-tips {
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.3);
    border-radius: 8px;
    padding: 16px;
    text-align: left;
}

.tutorial-tips h4 {
    color: #3b82f6;
    margin-bottom: 12px;
}

.tutorial-tips ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.tutorial-tips li {
    color: #cbd5e1;
    padding: 8px 0;
}

.resource-guide {
    display: grid;
    gap: 12px;
}

.resource-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 12px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
}

.resource-badge {
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 600;
    min-width: 80px;
}

.resource-badge.metal {
    background: linear-gradient(135deg, #6b7280, #9ca3af);
    color: white;
}

.resource-badge.crystal {
    background: linear-gradient(135deg, #3b82f6, #60a5fa);
    color: white;
}

.resource-badge.deuterium {
    background: linear-gradient(135deg, #8b5cf6, #a78bfa);
    color: white;
}

.resource-badge.energy {
    background: linear-gradient(135deg, #fbbf24, #fde047);
    color: #1e293b;
}

.goals-list {
    text-align: left;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.goal-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
}

.goal-item:hover {
    background: rgba(255, 255, 255, 0.08);
}

.tutorial-footer {
    padding: 24px;
    border-top: 1px solid rgba(59, 130, 246, 0.2);
}

.tutorial-progress {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-bottom: 24px;
}

.progress-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(59, 130, 246, 0.3);
    transition: all 0.3s;
}

.progress-dot.active {
    width: 24px;
    background: #3b82f6;
    border-radius: 4px;
}

.tutorial-actions {
    display: flex;
    justify-content: center;
    gap: 16px;
}

.btn-tutorial-prev,
.btn-tutorial-next,
.btn-tutorial-start {
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-tutorial-prev {
    background: transparent;
    border: 1px solid rgba(59, 130, 246, 0.5);
    color: #3b82f6;
}

.btn-tutorial-next,
.btn-tutorial-start {
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    border: none;
    color: white;
}

.btn-tutorial-start {
    background: linear-gradient(135deg, #10b981, #34d399);
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
let currentStep = 1;
const totalSteps = 3;

function showStep(step) {
    document.querySelectorAll('.tutorial-step').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.progress-dot').forEach(el => el.classList.remove('active'));
    
    document.querySelector(`.tutorial-step[data-step="${step}"]`).classList.add('active');
    document.querySelector(`.progress-dot[data-step="${step}"]`).classList.add('active');
    
    // Update buttons visibility
    document.querySelector('.btn-tutorial-prev').style.display = step === 1 ? 'none' : 'block';
    document.querySelector('.btn-tutorial-next').style.display = step === totalSteps ? 'none' : 'block';
    document.querySelector('.btn-tutorial-start').style.display = step === totalSteps ? 'block' : 'none';
}

function nextStep() {
    if (currentStep < totalSteps) {
        currentStep++;
        showStep(currentStep);
    }
}

function previousStep() {
    if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
    }
}

function closeTutorial() {
    document.getElementById('welcomeTutorial').style.display = 'none';
    // Set a cookie/localStorage to remember tutorial was seen
    localStorage.setItem('tutorialSeen', 'true');
}

function startPlaying() {
    closeTutorial();
}

// Show tutorial on first login
document.addEventListener('DOMContentLoaded', function() {
    if (!localStorage.getItem('tutorialSeen') && document.getElementById('welcomeTutorial')) {
        document.getElementById('welcomeTutorial').style.display = 'flex';
    }
});
</script>
