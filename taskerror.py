import pandas as pd
from scipy.stats import friedmanchisquare
import seaborn as sns
import matplotlib.pyplot as plt

# Load data
df = pd.read_csv("data.csv")

# Fix column name
df = df.rename(columns={
    "Task perfomance error (from total 7 objectives)": "TaskError"
})

# Convert Task numbers to labels
df["Task"] = df["Task"].replace({1: "Low", 2: "High", 3: "None"})

# Pivot for Friedman test
pivot = df.pivot(index="ParticipantID", columns="Task", values="TaskError")

# Friedman test
stat, p = friedmanchisquare(pivot["Low"], pivot["High"], pivot["None"])

# Kendall's W
n = len(pivot)
kendalls_w = stat / (n * 2)

print("\n=== TASK ERROR RESULTS ===")
print("Chi-square:", stat)
print("p-value:", p)
print("Kendall's W:", kendalls_w)

# Simple boxplot
plt.figure(figsize=(7,5))
sns.boxplot(data=df, x="Task", y="TaskError", palette="Set2")
plt.title("Task Error Across Conditions")
plt.xlabel("Condition")
plt.ylabel("Task Error")
plt.show()