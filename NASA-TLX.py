import pandas as pd
from scipy.stats import friedmanchisquare
import seaborn as sns
import matplotlib.pyplot as plt

# Load data
df = pd.read_csv("data.csv")

# Create NASA TLX total
df["NASA_TLX_Total"] = (
    df["Mental"] +
    df["Physical"] +
    df["Temporal"] +
    df["Performance"] +
    df["Effort"] +
    df["Frustration"]
)

# Convert Task numbers to labels
df["Task"] = df["Task"].replace({1: "Low", 2: "High", 3: "None"})

# Pivot for Friedman test
pivot = df.pivot(index="ParticipantID", columns="Task", values="NASA_TLX_Total")

# Friedman test
stat, p = friedmanchisquare(pivot["Low"], pivot["High"], pivot["None"])

# Kendall's W
n = len(pivot)
kendalls_w = stat / (n * 2)

print("\n=== NASA TLX TOTAL RESULTS ===")
print("Chi-square:", stat)
print("p-value:", p)
print("Kendall's W:", kendalls_w)

# Simple boxplot
plt.figure(figsize=(7,5))
sns.boxplot(data=df, x="Task", y="NASA_TLX_Total", palette="Pastel1")
plt.title("NASA TLX Total Across Conditions")
plt.xlabel("Condition")
plt.ylabel("NASA TLX Total Score")
plt.show()