## php src learning notes
php代码来自php.net[php-8.0.6](https://www.php.net/distributions/php-8.0.6.tar.gz),注释笔记都是记录
在行尾或者空行上、不新增行。主要记录、总结自己的学习所得。如果对你也有用，那很高兴能帮到你。

### design programming language
设计一门编程语言可以参看[Build Your Own Lisp](http://buildyourownlisp.com/contents)，我的代码实现也
在[buildyourownlisp](https://github.com/jamaltan/buildyourownlisp)

程序 = 代码 + 数据，程序本身也是接受一个输入，在一个规则或者一定的作用下得到一个得到一定的输出结果。
在数学中我们也说输入为变量，输出为应变量，而规则或者作用为函数。

那来尝试思考下面几个问题：
- 编程语言lisp,php...具体是怎么执行的
- builtin function、key words...是如何识别出来的
- expr 表达式是如何求值的...

那我们就以php为例，梳理一下整体的逻辑：
- php代码需要通过词法解析器lexer，将php代码这个很长的字符串解析成有意义的token
- 然后经过语法解析器将token抽象成抽象语法树AST
- 基于AST递归求值，每一个AST的节点都有对应与之匹配的预定义规则
- 基于特定的规则求值、然后递归求值

![Image of Programming language](https://github.com/PositiveTechnologies/PT.Doc/blob/master/Articles/Theory-and-Practice-of-source-code-parsing-with-ANTLR-and-Roslyn/Lexer-and-Parser.png)

### questions
- 如何编写一个通用的函数，比如排序功能


```c
typedef int  (*compare_func_t)(const void *, const void *);
typedef void (*swap_func_t)(void *, void *);
typedef void (*sort_func_t)(void *, size_t, size_t, compare_func_t, swap_func_t);
```

其实可以假想让自己写一个通用的排序算法，虽然这个需求在实际业务开发场景中相对少见。但是真的让自己开发的
话，那这个**通用**那肯定尽可能的让这个排序算法不和特定的数据类型耦合在一起。这样可以避免一套代码需要针对
int，float...不通的数据类型来实现一遍。那就需要尽可能的将通用代码写在一起，这个通用代码我自己一般也称为
**控制逻辑代码**，而具体和相关数据类型耦合在一起的这部分代码称为**业务逻辑代码**或者是业务相关代码。这两
个名词也是我自己的理解，就是表达一个准确的意思。

那我们这个控制逻辑代码，主要起逻辑控制的。比如一个通用的排序算法可以针对排序数据的数据体量选择不一样的排序
算法，毕竟不一样的排序算法有不一样的时间复杂度。冒泡排序(bubble sort)、插入排序(insertion sort)、选择排序
(selection sort)的时间复杂度O(n^2),快速排序(quick sort)的时间负责度O(nlogn)，当然不通算法的稳定性也是不一样。
那么就可以在控制逻辑上来选择， 针对不一样的数据类型比较规则不一样，那么就可以将这个比较的功能抽离处理，
提供一个函数给调用方caller，让调用方自定义来解决

所以php里实现的排序函数和qsort函数的参数基本一样，主要有：
- start of the array 起始地址
- number of element 元素个数
- size of element 单个元素的大小 
- compare function 提个一个比较函数
