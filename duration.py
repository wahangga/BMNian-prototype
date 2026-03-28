import pandas as pd
from scipy.stats import friedmanchisquare
import seaborn as sns
import matplotlib.pyplot as plt

# Load data
df = pd.read_csv("data.csv")

# Convert Task numbers to labels
df["Task"] = df["Task"].replace({1: "Low", 2: "High", 3: "None"})

# Pivot for Friedman test
pivot = df.pivot(index="ParticipantID", columns="Task", values="Duration")

# Friedman test
stat, p = friedmanchisquare(pivot["Low"], pivot["High"], pivot["None"])

# Kendall's W
n = len(pivot)
kendalls_w = stat / (n * 2)

print("\n=== TASK DURATION RESULTS ===")
print("Chi-square:", stat)
print("p-value:", p)
print("Kendall's W:", kendalls_w)

# Simple boxplot
plt.figure(figsize=(7,5))
sns.boxplot(data=df, x="Task", y="Duration", palette="Set3")
plt.title("Task Duration Across Conditions")
plt.xlabel("Condition")
plt.ylabel("Duration (seconds)")
plt.show()