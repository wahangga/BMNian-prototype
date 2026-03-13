<?php
// --- BACKEND ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    
    if ($input['action'] === 'call_ai') {

        $apiKey = "";
        // using gemini-3.1-flash-lite-preview
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite-preview:generateContent?key=" . $apiKey;
    
        $payload = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $input['prompt']]
                    ]
                ]
            ]
        ];
    
        $ch = curl_init($url);
    
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
            CURLOPT_POSTFIELDS => json_encode($payload)
        ]);
    
        $response = curl_exec($ch);
    
        if (curl_errno($ch)) {
            echo json_encode([
                "error" => curl_error($ch)
            ]);
            curl_close($ch);
            exit;
        }
    
        curl_close($ch);
    
        header('Content-Type: application/json');
        echo $response;
        exit;
    }

    if ($input['action'] === 'send_results') {

        $to = "takstarhp@gmail.com, mojabalmejbel676@gmail.com";
        $from = "wahangganteng@gmail.com";
        $subject = "RESEARCH DATA: Email AI Study - " . date("Y-m-d H:i:s");
    
        $body = "PARTICIPANT SUMMARY\n";
        $body .= "============================\n\n";
        $body .= json_encode($input['results'], JSON_PRETTY_PRINT);
        $body .= "\n\nSubmitted at: " . date("Y-m-d H:i:s");
    
        $headers = [];
        $headers[] = "From: $from";
        $headers[] = "Reply-To: $from";
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: text/plain; charset=UTF-8";
    
        $success = mail($to, $subject, $body, implode("\r\n", $headers));
    
        header('Content-Type: application/json');
        echo json_encode([
            "sent" => $success
        ]);
    
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HCI Research Study</title>
    <style>
        :root { --uu-yellow: #ffcd00; --bg: #f4f4f4; --text: #333; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: var(--bg); margin: 0; color: var(--text); }
        #app { width: 100vw; height: 100vh; display: flex; flex-direction: column; }
        
        /* Welcome Screen */
        .screen { display: none; flex-direction: column; align-items: center; justify-content: center; height: 100%; padding: 50px; box-sizing: border-box; background: white; }
        .active { display: flex; }
        .hero-box { max-width: 600px; text-align: center; border-top: 8px solid var(--uu-yellow); padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); border-radius: 4px; }
        
        /* Layouts */
        .experiment-grid { display: grid; grid-template-columns: 3fr 1fr; height: 100%; width: 100%; }
        .editor-area { padding: 40px; background: #fafafa; border-right: 1px solid #ddd; overflow-y: auto; }
        .ai-sidebar { padding: 30px; background: #eeeeee; }
        
        /* Objectives */
        .obj-box { background: white; border: 1px solid #ccc; padding: 20px; margin-bottom: 25px; font-size: 14px; }
        .obj-item { margin-bottom: 8px; border-bottom: 1px solid #eee; padding-bottom: 4px; }
        
        /* Input Styling */
        input.field { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        textarea.field { width: 100%; height: 300px; padding: 15px; border: 1px solid #ccc; border-radius: 4px; resize: none; font-size: 15px; line-height: 1.6; }
        
        /* NASA TLX */
        .survey-container { width: 100%; max-width: 700px; overflow-y: auto; max-height: 70vh; padding: 20px; border: 1px solid #eee; }
        .tlx-row { margin-bottom: 30px; }
        input[type=range] { width: 100%; margin: 15px 0; }
        
        .btn { background: #333; color: white; border: none; padding: 15px 40px; cursor: pointer; font-size: 16px; border-radius: 4px; }
        .btn:hover { background: #000; }
        .ai-msg { background: white; padding: 20px; border-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-top: 20px; border-left: 5px solid var(--uu-yellow); }
        
        .consent-text {
            max-height: 500px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 15px;
            margin: 15px 0;
            background: #fafafa;
        }
        
        .consent-check label {
            display: block;
            margin-bottom: 10px;
        }

    </style>
</head>
<body>

<div id="app">
    <div id="screen-welcome" class="screen active">
        <div class="hero-box">
            <h1>Welcome to BMNian experiment</h1>
            <!--<p>Thank you for participating in our study</p>-->
            <p style="font-size: 0.9em; color: #666;">This study takes approximately 10-15 minutes.</p>
            <button class="btn" onclick="showScreen('screen-consent')">Begin Study</button>
        </div>
    </div>

    <div id="screen-consent" class="screen">
        <div class="hero-box" style="text-align:left; max-width:700px; margin:auto;">
    
            <h2>Informed Consent</h2>
    
            <!-- Scrollable study info -->
            <div class="consent-text">
                <p><strong>Study Title:</strong> The Impact of AI Suggestion Frequency on Student Cognitive Workload and Performance</p>
    
                <p><strong>Researchers:</strong> This study is being conducted by Mojab Almejbel, I Gusti Agung Ngurah Anggawijaya, and Bharath Kumar Sokke Prabhu, master's students in the HCI program at Utrecht University.</p>
    
                <p><strong>Invitation and Purpose</strong><br>
                You are invited to participate in a study on how AI suggestions affect the experience of writing an email. The goal is to measure cognitive load, the mental effort required to complete a task.</p>
    
                <p><strong>What Participation Involves</strong><br>
                You will be asked to write an email that meets 5 specific objectives. While writing, an AI will provide you with suggestions. After completing the email, you will answer a few short questions to rate the mental effort the task required. The entire session will take approximately 10-15 minutes.</p>
    
                <p><strong>Use of Data</strong><br>
                Your email content, the objectives you met, and your ratings will be collected for analysis. This data will be used to draw conclusions about cognitive load and performance when using AI writing tools.</p>
    
                <p><strong>Potential Risks and Your Rights</strong><br>
                Participation is voluntary. There are no anticipated risks beyond those of normal computer use. You may:</p>
                <ul>
                    <li>Take breaks at any time,</li>
                    <li>Stop your participation, or</li>
                    <li>Withdraw from the study entirely, with no consequences.</li>
                </ul>
                <p>If you withdraw, you can request that your data be deleted by contacting the researcher.</p>
    
                <p><strong>Confidentiality</strong><br>
                No personally identifiable information (like your name) will be stored with your data. Your responses will be saved anonymously using a participant code and stored securely on a password-protected university server, accessible only to the research team. Anonymized data will be retained for 10 years in line with university policy.</p>
    
                <p><strong>Contact</strong><br>
                For questions about the study, contact:</p>
                <ul>
                    <li>m.almejbel@students.uu.nl</li>
                    <li>a.igustiagungngurahanggawijaya@students.uu.nl</li>
                    <li>b.k.sokkeprabhu@students.uu.nl</li>
                </ul>
                
                <p><strong>For complaints or concerns:</strong></p>
                <ul>
                    <li>Ethics Review Board: etc-beta-geo@uu.nl</li>
                    <li>Faculty Privacy Officer: privacy-beta@uu.nl</li>
                </ul>
            </div>
    
            <p>Please confirm your understanding by checking the boxes below:</p>
    
            <!-- Fixed area -->
            <div class="consent-check">
                <label><input type="checkbox" class="c-check"> I have read and understood the information above.</label>
                <label><input type="checkbox" class="c-check"> I understand participation is voluntary.</label>
                <label><input type="checkbox" class="c-check"> I agree to participate in this study.</label>
            </div>
    
            <button class="btn" onclick="checkConsent()">Accept & Continue</button>
    
        </div>
    </div>



    <div id="screen-instruction" class="screen">
        <div class="hero-box" style="text-align: left;">
            <h2>Task Instructions</h2>
            <p>You will complete 3 email writing scenarios.</p>
            <ul>
                <li>Follow the <b>Recipient, Subject,</b> and <b>5 points</b> for each task.</li>
                <li>Try to complete the objectives as naturally as possible.</li>
                <li>After each task, you will answer a short workload survey.</li>
            </ul>
            <button class="btn" onclick="startTask(1)">I'm Ready, Start Task 1</button>
        </div>
    </div>

    <div id="screen-task" class="screen" style="padding:0; align-items: stretch;">
        <div class="experiment-grid">
            <div class="editor-area">
                <h3 id="task-name">Task 1</h3>
                <div class="obj-box" id="obj-list"></div>
                <input id="f-to" class="field" placeholder="To:">
                <input id="f-sub" class="field" placeholder="Subject:">
                <textarea id="f-body" class="field" placeholder="Write your email here..." onkeyup="handleTyping()"></textarea>
                <div style="text-align:right; margin-top:20px;">
                    <button class="btn" onclick="finishTask()">Send Email</button>
                </div>
            </div>
            <div class="ai-sidebar">
                <h3>AI Assistant</h3>
                <div id="ai-status" style="color:#888; font-style:italic;">Inactive for this task</div>
                <div id="ai-box" style="display:none;">
                </div>
            </div>
        </div>
    </div>

    <div id="screen-tlx" class="screen">
        <h2>Task Workload Assessment</h2>
        <div class="survey-container" id="tlx-form"></div>
        <button class="btn" style="margin-top:30px;" onclick="saveSurvey()">Submit & Next</button>
    </div>

    <div id="screen-thanks" class="screen">
        <div class="hero-box">
            <h2>Thank you for your participation</h2>
            <p>Your data has been successfully transmitted.</p>
            <p><b>You may now close this window.</b></p>
        </div>
    </div>
</div>

<script>
    let currentTask = 1;
    let timerStart;
    let aiTriggerCount = 0;
    let allResults = [];
    let currentAIStats = { accept: 0, reject: 0, ignore: 0 };
    let aiSuggestions = [];
    let participantID='P-'+Date.now().toString(36)+'-'+Math.random().toString(36).substring(2,8);

    const tasks = {
        1: { limit: 0, to: "manager@office.com", sub: "Status Update", points: ["1. Project on track", "2. Met 3 milestones", "3. Need budget review", "4. Schedule meeting", "5. Thanks for support"] },
        2: { limit: 3, to: "it-support@uu.nl", sub: "Login Error", points: ["1. Cannot access portal", "2. Error code 505", "3. Tried clearing cache", "4. Exam is tomorrow", "5. Urgent help needed"] },
        3: { limit: 5, to: "recruitment@global.com", sub: "Application Follow-up", points: ["1. Interviewed last week", "2. Reiterate interest", "3. Mention relevant skill", "4. Attach portfolio link", "5. Ask for timeline"] }
    };

    function showScreen(id) {
        document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
        document.getElementById(id).classList.add('active');
    }

    function checkConsent() {
        const checked = Array.from(document.querySelectorAll('.c-check')).every(c => c.checked);
        if (checked) showScreen('screen-instruction');
        else alert("Please check all boxes to proceed.");
    }

    function startTask(num) {
        currentTask = num;
        aiTriggerCount = 0;
        currentAIStats = { accept: 0, reject: 0, ignore: 0 };
        aiSuggestions = [];
        
        // RESET FIELDS
        document.getElementById('f-to').value = "";
        document.getElementById('f-sub').value = "";
        document.getElementById('f-body').value = "";
        document.getElementById('ai-box').style.display = 'none';
        document.getElementById('ai-box').innerHTML = "";
        
        document.getElementById('task-name').innerText = "Task " + num;
        let objHtml = `<b>Write an email</b><br><br><b>To:</b> ${tasks[num].to}<br><b>Subject:</b> ${tasks[num].sub}<br><br><b>Mention this points in the email:</b><hr>`;
        tasks[num].points.forEach(p => objHtml += `<div class='obj-item'>${p}</div>`);
        document.getElementById('obj-list').innerHTML = objHtml;
        
        document.getElementById('ai-status').innerText = tasks[num].limit > 0 ? "Ready to assist..." : "Inactive for this task";
        
        timerStart = Date.now();
        showScreen('screen-task');
    }

    function handleTyping() {
        const config = tasks[currentTask];
        if (config.limit > 0 && aiTriggerCount < config.limit) {
            let body = document.getElementById('f-body').value;
            // TRIGGER AI EVERY 25 CHARACTERS
            if (body.length > 0 && body.length % 17 === 0) {
                callGemini(body);
            }
        }
    }

    async function callGemini(text) {
        aiTriggerCount++;
        document.getElementById('ai-status').innerText = "AI is thinking...";
        
        let taskContext = `Task: Write an email to ${tasks[currentTask].to} with subject "${tasks[currentTask].sub}" covering these points: ${tasks[currentTask].points.join(', ')}.`;
        
        let prompt;
        if (aiTriggerCount === 1) {
            prompt = `Don't give full email suggestions. Check if the recipient "${document.getElementById('f-to').value}" is correct. It should be "${tasks[currentTask].to}". If incorrect, say "The recipient may be incorrect, it should be ${tasks[currentTask].to}." If correct, say "The recipient looks correct." Do not use bold, markdown, options, or multiple suggestions.`;
        } else if (aiTriggerCount === 2) {
            prompt = `Don't give full email suggestions. Evaluate if the subject "${document.getElementById('f-sub').value}" is related to "${tasks[currentTask].sub}". If similarity is over 75%, say "The subject looks good." Else say "The subject may be incorrect, it should be ${tasks[currentTask].sub}." Do not use bold, markdown, options, or multiple suggestions.`;
        } else {
            const objIndex = aiTriggerCount - 3;
            const obj = tasks[currentTask].points[objIndex] || tasks[currentTask].points[0];
            prompt = `${taskContext} The user has written in the body: "${text}". Based on objective: ${obj}. Suggest ONLY the next 1 sentence to continue the email. Do not provide full sentences, paragraphs, or complete suggestions. Keep it very short and incremental.`;
        }
        
        try {
            const response = await fetch('qnmexperiment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'call_ai', prompt: prompt })
            });
            const data = await response.json();
            
            // handle response
            let suggestion = null;
            if (data.candidates && data.candidates[0].content.parts[0].text) {
                suggestion = data.candidates[0].content.parts[0].text;
            } else if (data.output && data.output.length > 0) {
                const out = data.output[0];
                if (out && out.content && out.content.length > 0) {
                    suggestion = out.content.map(c => c.text || '').join('');
                }
            }
            if (suggestion) {
                // if AI returns an empty or "no suggestion" response, replace with a general suggestion
                let displayText = suggestion;
                if (!suggestion || /no suggestion/i.test(suggestion)) {
                    displayText = "Please check the recipient email address, verify the subject line, and review the task objectives.";
                }
                aiSuggestions.push(displayText);
                
                // display latest suggestion
                const suggestionDiv = document.createElement('div');
                suggestionDiv.className = 'ai-msg';
                suggestionDiv.innerHTML = `
                    <p style="font-size: 14px; line-height: 1.4;">${displayText}</p>
                    <div style="margin-top:15px; display:flex; gap:5px;">
                        <button onclick="aiAction('accept', this)" style="flex:1;">Accept</button>
                        <button onclick="aiAction('reject', this)" style="flex:1;">Reject</button>
                        <button onclick="aiAction('ignore', this)" style="flex:1;">Ignore</button>
                    </div>
                `;
                document.getElementById('ai-box').appendChild(suggestionDiv);
                document.getElementById('ai-box').style.display = 'block';
                document.getElementById('ai-status').innerText = "Suggestion ready.";
            } else {
                const suggestionDiv = document.createElement('div');
                suggestionDiv.className = 'ai-msg';
                suggestionDiv.innerHTML = `
                    <p style="font-size: 14px; line-height: 1.4;">No suggestion available.</p>
                    <div style="margin-top:15px; display:flex; gap:5px;">
                        <button onclick="aiAction('accept', this)" style="flex:1;">Accept</button>
                        <button onclick="aiAction('reject', this)" style="flex:1;">Reject</button>
                        <button onclick="aiAction('ignore', this)" style="flex:1;">Ignore</button>
                    </div>
                `;
                document.getElementById('ai-box').appendChild(suggestionDiv);
                document.getElementById('ai-box').style.display = 'block';
                document.getElementById('ai-status').innerText = "Suggestion ready.";
            }
        } catch (e) {
            console.error("AI Error:", e);
            const suggestionDiv = document.createElement('div');
            suggestionDiv.className = 'ai-msg';
            suggestionDiv.innerHTML = `
                <p style="font-size: 14px; line-height: 1.4;">Error fetching suggestion: ${e.message}</p>
                <div style="margin-top:15px; display:flex; gap:5px;">
                    <button onclick="aiAction('accept', this)" style="flex:1;">Accept</button>
                    <button onclick="aiAction('reject', this)" style="flex:1;">Reject</button>
                    <button onclick="aiAction('ignore', this)" style="flex:1;">Ignore</button>
                </div>
            `;
            document.getElementById('ai-box').appendChild(suggestionDiv);
            document.getElementById('ai-box').style.display = 'block';
            document.getElementById('ai-status').innerText = "Error occurred.";
        }
    }

    function aiAction(type, button) {
        currentAIStats[type]++;
        // suggestion

        const suggestion = button.parentElement.previousElementSibling.innerText;

        if (type === 'accept') {
            
            // handle recipient correction
            if (suggestion.toLowerCase().includes('recipient may be incorrect')) {
                const match = suggestion.match(/it should be ([\w@.\-]+)/i);
                if (match) {
                    document.getElementById('f-to').value = match[1];
                }
            }
            // handle subject correction separately; do not append any body text in this case
            else if (suggestion.toLowerCase().includes('subject may be incorrect')) {
                const match = suggestion.match(/it should be ([^\.]*)/i);
                if (match) {
                    document.getElementById('f-sub').value = match[1].trim();
                }
            }
            // otherwise treat it as a body suggestion
            else {
                const currentBody = document.getElementById('f-body').value;
                const cleanSuggestion = suggestion.replace(/"/g, '').trim();
                document.getElementById('f-body').value = currentBody + ' ' + cleanSuggestion;
            }
        }
        
        // Disable buttons for this suggestion
        button.parentElement.querySelectorAll('button').forEach(btn => btn.disabled = true);
    }

    function finishTask() {
        const to = document.getElementById('f-to').value.trim();
        const sub = document.getElementById('f-sub').value.trim();
        const body = document.getElementById('f-body').value.trim();
        
        if (!to || !sub || !body) {
            alert("Please fill in all fields (To, Subject, and Body) before sending the email.");
            return;
        }
        
        const duration = (Date.now() - timerStart) / 1000;
        allResults.push({ 
            task: currentTask, 
            duration: duration, 
            ai: {...currentAIStats}, 
            inputs: {
                to: to,
                sub: sub,
                body: body
            },
            aiSuggestions: [...aiSuggestions]
        });
        setupTLX();
        showScreen('screen-tlx');
    }

    function setupTLX() {
        const dims = [
            { id: "mental", n: "Mental Demand", d: "How much mental and perceptual activity was required for the task? Consider the complexity, decision‑making, and attentional demands." },
            { id: "physical", n: "Physical Demand", d: "How much physical activity was required? Consider the amount of physical effort needed to complete the task." },
            { id: "temporal", n: "Temporal Demand", d: "How much time pressure did you feel during the task? Consider whether the pace was slow, relaxed, rapid, or frantic." },
            { id: "perf", n: "Performance", d: "How successful were you in accomplishing the task’s goals? Consider how satisfied you are with your performance." },
            { id: "effort", n: "Effort", d: "How hard did you have to work mentally and physically to achieve your level of performance?" },
            { id: "frust", n: "Frustration", d: "How insecure, discouraged, irritated, stressed, or annoyed did you feel during the task?" }
        ];
        let html = "";
        dims.forEach(d => {
            html += `<div class="tlx-row">
                <label>${d.n}</label><br><span style="font-size:12px; color:#666;">${d.d}</span>
                <input type="range" min="1" max="10" id="tlx-${d.id}">
                <div style="display:flex; justify-content:space-between; font-size:10px;"><span>Very Low</span><span>Very High</span></div>
            </div>`;
        });
        document.getElementById('tlx-form').innerHTML = html;
    }

    function saveSurvey() {
        const last = allResults[allResults.length - 1];
        last.tlx = {
            mental: document.getElementById('tlx-mental').value,
            phys: document.getElementById('tlx-physical').value,
            temp: document.getElementById('tlx-temporal').value,
            perf: document.getElementById('tlx-perf').value,
            eff: document.getElementById('tlx-effort').value,
            frust: document.getElementById('tlx-frust').value
        };
        
        if (currentTask < 3) startTask(currentTask + 1);
        else sendFinalData();
    }

    async function sendFinalData() {
        const data = { participantID:participantID, results: allResults };
        
        // Automatically download results to user's PC
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'experiment_results.json';
        a.click();
        URL.revokeObjectURL(url);
        
        // Send results via email
        try {
            const response = await fetch('qnmexperiment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'send_results', ...data })
            });
            const result = await response.json();
            console.log('Email send result:', result);
            if (!result.sent) {
                alert('Email sending failed. Check console for details.');
            }
        } catch (e) { 
            console.error("Email Error:", e); 
            alert('Error sending email: ' + e.message);
        }
        showScreen('screen-thanks');
    }
</script>

</body>
</html>
