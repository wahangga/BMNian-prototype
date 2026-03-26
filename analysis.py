import pandas as pd
import numpy as np
from scipy.stats import friedmanchisquare, wilcoxon
import matplotlib.pyplot as plt
import seaborn as sns

# ---------------------------------------------------------
# 1. LOAD YOUR DATA
# ---------------------------------------------------------

# Example structure — replace with your real data
# Each row = participant
# Columns = the 3 conditions for one DV (e.g., Duration)
data = pd.DataFrame({
    "Low":    [291.8, 73.2, 88.6, 639.7, 391.8],
    "High":   [157.6, 106.2, 106.9, 325.9, 194.0],
    "None":   [435.3, 92.7, 0, 0, 295.5]  # replace zeros with real values
})

print("Your dataset:")
print(data)

# ---------------------------------------------------------
# 2. FRIEDMAN TEST
# ---------------------------------------------------------

stat, p = friedmanchisquare(data["Low"], data["High"], data["None"])
print("\nFriedman Test:")
print("Chi-square =", stat)
print("p-value =", p)

# ---------------------------------------------------------
# 3. KENDALL'S W (Effect Size)
# ---------------------------------------------------------

n = len(data)      # number of participants
k = 3              # number of conditions

kendalls_w = stat / (n * (k - 1))
print("\nKendall's W =", kendalls_w)

# ---------------------------------------------------------
# 4. WILCOXON SIGNED-RANK TESTS (Pairwise)
# ---------------------------------------------------------

print("\nWilcoxon Pairwise Comparisons:")

# Low vs High
z1, p1 = wilcoxon(data["Low"], data["High"])
print("Low vs High: Z =", z1, "p =", p1)

# Low vs None
z2, p2 = wilcoxon(data["Low"], data["None"])
print("Low vs None: Z =", z2, "p =", p2)

# High vs None
z3, p3 = wilcoxon(data["High"], data["None"])
print("High vs None: Z =", z3, "p =", p3)

# ---------------------------------------------------------
# 5. VISUALIZATION — BOXPLOT
# ---------------------------------------------------------

plt.figure(figsize=(8,5))
sns.boxplot(data=data)
plt.title("Task Duration Across Conditions")
plt.ylabel("Duration (seconds)")
plt.show()

# ---------------------------------------------------------
# 6. VISUALIZATION — LINE PLOT (Participant Trends)
# ---------------------------------------------------------

plt.figure(figsize=(8,5))
plt.plot(data.T, marker='o')
plt.title("Participant Performance Across Conditions")
plt.xlabel("Condition")
plt.ylabel("Duration")
plt.show()

# ---------------------------------------------------------
# 7. VISUALIZATION — BAR CHART (Means)
# ---------------------------------------------------------

means = data.mean()
plt.figure(figsize=(8,5))
sns.barplot(x=means.index, y=means.values)
plt.title("Mean Duration per Condition")
plt.ylabel("Mean Duration")
plt.show()