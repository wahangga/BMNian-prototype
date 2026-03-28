import pandas as pd
import numpy as np
from scipy.stats import friedmanchisquare
import matplotlib.pyplot as plt
import seaborn as sns

# Load data
df = pd.read_csv("data.csv")
df = df.rename(columns={
    "Task perfomance error (from total 7 objectives)": "TaskError"
})

# All variables you want to test
variables = [
    "Duration", "Mental", "Physical", "Temporal",
    "Performance", "Effort", "Frustration",
    "CognitiveError", "TaskError"
]

# Combined Friedman test for all variables
print("\n========== FRIEDMAN TEST RESULTS ==========\n")

results = []

for dv in variables:
    pivot = df.pivot(index="ParticipantID", columns="Task", values=dv)
    pivot.columns = ["Low", "High", "None"]

    stat, p = friedmanchisquare(pivot["Low"], pivot["High"], pivot["None"])

    n = len(pivot)
    k = 3
    kendalls_w = stat / (n * (k - 1))

    results.append([dv, stat, p, kendalls_w])

# Convert results to table
results_df = pd.DataFrame(results, columns=["Variable", "Chi-square", "p-value", "Kendall_W"])
print(results_df)

# Combined visualization for all variables
melted = df.melt(
    id_vars=["ParticipantID", "Task"],
    value_vars=variables,
    var_name="Measure",
    value_name="Score"
)

# Replace task numbers with labels
melted["Task"] = melted["Task"].replace({1: "Low", 2: "High", 3: "None"})

# Boxplot for all variables
plt.figure(figsize=(14, 8))
sns.boxplot(data=melted, x="Task", y="Score", hue="Measure")
plt.title("All Measures Across AI Suggestion Conditions")
plt.legend(bbox_to_anchor=(1.05, 1), loc='upper left')
plt.show()

# Violin plot for all variables
plt.figure(figsize=(14, 8))
sns.violinplot(data=melted, x="Task", y="Score", hue="Measure", split=False)
plt.title("Distribution of All Measures Across Conditions")
plt.legend(bbox_to_anchor=(1.05, 1), loc='upper left')
plt.show()

# Participant trend lines for all variables
plt.figure(figsize=(14, 8))
for pid in df["ParticipantID"].unique():
    subset = melted[melted["ParticipantID"] == pid]
    plt.plot(subset["Task"], subset["Score"], alpha=0.3)
plt.title("Participant Trends Across All Measures")
plt.show()